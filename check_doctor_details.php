<?php
include('doctor/includes/dbconnection.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doctorName'])) {
    $doctorName = trim($_POST['doctorName']);

    if (empty($doctorName)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter a doctor name.']);
        exit;
    }

    // Fetch doctor details (EXACT MATCH)
    $sql = "SELECT d.ID, d.FullName, d.Email, s.Specialization, 
                   TIMESTAMPDIFF(YEAR, d.CreationDate, CURDATE()) AS YearsExperience
            FROM tbldoctor d
            JOIN tblspecialization s ON d.Specialization = s.ID
            WHERE d.FullName = :doctorName";

    $query = $dbh->prepare($sql);
    $query->bindParam(':doctorName', $doctorName, PDO::PARAM_STR);
    $query->execute();
    $doctor = $query->fetch(PDO::FETCH_ASSOC);

    if ($doctor) {
        $doctorId = $doctor['ID'];

        // Fetch appointment data
        $sql_appointments = "SELECT DATE(AppointmentDate) AS AppDate, COUNT(*) AS Total 
                             FROM tblappointment WHERE Doctor = :doctorId 
                             GROUP BY DATE(AppointmentDate) ORDER BY AppointmentDate ASC";
        
        $query_appointments = $dbh->prepare($sql_appointments);
        $query_appointments->bindParam(':doctorId', $doctorId, PDO::PARAM_INT);
        $query_appointments->execute();
        $appointments = $query_appointments->fetchAll(PDO::FETCH_ASSOC);

        $dates = [];
        $counts = [];

        foreach ($appointments as $row) {
            $dates[] = $row['AppDate'];
            $counts[] = $row['Total'];
        }

        echo json_encode([
            'status' => 'success',
            'data' => $doctor,
            'chartData' => ['dates' => $dates, 'counts' => $counts]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Doctor not found.']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Search</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .search-container {
            text-align: center;
            padding: 20px;
        }
        .form-control {
            border: 2px solid #007bff;
            transition: 0.3s;
        }
        .form-control:focus {
            box-shadow: 0 0 10px #007bff;
        }
        .btn-primary {
            font-weight: bold;
            background-color: #007bff;
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .doctor-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            transition: 0.3s;
        }
        .doctor-card:hover {
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-3px);
        }
        #chart-container {
            height: 250px;
            width: 100%;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }
        #chart-container:hover {
            transform: scale(1.02);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.15);
        }
        canvas {
            max-height: 250px;
        }
    </style>
</head>
<body class="container mt-5">
    <h2 class="text-center mb-4 text-primary"> Doctor & Appointments</h2>

    <div class="row search-container">
        <div class="col-lg-6 col-12">
            <input id="doctorSearch" type="text" class="form-control" placeholder="Enter Doctor Name">
        </div>
        <div class="col-lg-3 col-md-4 col-6 mx-auto">
            <button class="btn btn-primary w-100" onclick="searchDoctor()">Search Doctor</button>
        </div>
    </div>

    <div id="doctorDetails"></div>

    <div id="chart-container" style="display: none;">
        <canvas id="appointmentChart"></canvas>
    </div>

    <script>
        function searchDoctor() {
            var doctorName = document.getElementById("doctorSearch").value.trim();
            if (doctorName === "") {
                alert("Please enter a doctor name.");
                return;
            }

            $.ajax({
                type: "POST",
                url: window.location.href,
                data: { doctorName: doctorName },
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        $("#doctorDetails").html(`
                            <div class="doctor-card">
                                <h4 class="text-primary">Doctor Details</h4>
                                <p><strong>Name:</strong> ${response.data.FullName}</p>
                                <p><strong>Specialty:</strong> ${response.data.Specialization}</p>
                                <p><strong>Email:</strong> ${response.data.Email}</p>
                                <p><strong>Years of Experience:</strong> ${response.data.YearsExperience}</p>
                            </div>
                        `);
                        
                        plotChart(response.chartData);
                    } else {
                        $("#doctorDetails").html(`<div class="alert alert-danger">${response.message}</div>`);
                        $("#chart-container").hide();
                    }
                }
            });
        }

        function plotChart(chartData) {
            var ctx = document.getElementById('appointmentChart').getContext('2d');
            document.getElementById('chart-container').style.display = 'block';

            if (window.myChart) {
                window.myChart.destroy();
            }

            window.myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.dates, // Use 'dates' on the x-axis
                    datasets: [{
                        label: 'Appointments Per Day',
                        data: chartData.counts, // Use 'counts' for the number of appointments
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        pointStyle: 'circle',
                        pointRadius: 5,
                        pointBackgroundColor: 'red'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: { display: true, text: 'Dates' } // Label for the x-axis is now 'Dates'
                        },
                        y: {
                            title: { display: true, text: 'Number of Appointments' } // Label for the y-axis is now 'Number of Appointments'
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
