<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the DB connection
include('includes/dbconnection.php');

// Handle feedback submission
if (isset($_POST['submit_feedback'])) {
    $feedback = trim($_POST['feedback']);
    if (!empty($feedback)) {
        $stmt = $dbh->prepare("INSERT INTO feedbacks (feedback) VALUES (?)");
        $stmt->bindParam(1, $feedback);
        if ($stmt->execute()) {
            echo json_encode(['success' => 'Feedback submitted successfully!']);
        } else {
            echo json_encode(['error' => 'Error submitting feedback!']);
        }
    } else {
        echo json_encode(['error' => 'Feedback cannot be empty!']);
    }
    exit;
}

// Handle fetching old feedbacks
if (isset($_POST['view_feedbacks'])) {
    $stmt = $dbh->query("SELECT id, feedback, created_at FROM feedbacks ORDER BY created_at DESC");
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['feedbacks' => $feedbacks]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedbacks</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* General Page Styling */
        body {
            background-color: #f4f7fa;
            font-family: 'Arial', sans-serif;
            color: #333;
            overflow-x: hidden;
        }

        .container {
            margin-top: 40px;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #3b5998;
            margin-bottom: 20px;
        }

        /* Success and Error Messages */
        .success-message {
            background-color: #28a745;
            color: white;
            padding: 15px;
            font-size: 18px;
            border-radius: 8px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.5s ease-out;
        }

        .error-message {
            background-color: #dc3545;
            color: white;
            padding: 15px;
            font-size: 18px;
            border-radius: 8px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Button Styling */
        #submitFeedback, #viewFeedbacks {
            font-size: 16px;
            padding: 12px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
            position: relative;
        }

        #submitFeedback {
            background-color: #007bff;
            color: white;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        #submitFeedback:hover {
            background-color: #0056b3;
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(0, 123, 255, 0.8);
        }

        #viewFeedbacks {
            background-color: #6c757d;
            color: white;
        }

        #viewFeedbacks:hover {
            background-color: #5a6268;
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(108, 117, 125, 0.8);
        }

        /* Button Border Light Animation */
        #submitFeedback:focus, #viewFeedbacks:focus {
            outline: none;
            box-shadow: 0 0 8px rgba(0, 123, 255, 1);
        }

        /* Scrolling Effect for Success Message */
        @keyframes slideIn {
            0% {
                transform: translateY(-20px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Form Styling */
        #feedbackForm textarea {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #ddd;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }

        /* Feedback List Styling */
        .list-group-item {
            border-radius: 8px;
            padding: 15px;
            background-color: #f8f9fa;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
        }

        .list-group-item:hover {
            background-color: #e9ecef;
        }

        .text-muted {
            font-size: 14px;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            #feedbackForm textarea {
                font-size: 14px;
            }

            #submitFeedback, #viewFeedbacks {
                font-size: 14px;
                padding: 10px 15px;
            }
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Submit Your Feedback</h2>

    <!-- Display success or error messages -->
    <div id="message"></div>

    <!-- Feedback Form -->
    <form id="feedbackForm" class="mb-3">
        <div class="mb-3">
            <textarea name="feedback" id="feedback" class="form-control" placeholder="Enter your feedback here..." rows="4" required></textarea>
        </div>
        <button type="submit" id="submitFeedback" class="btn">Submit</button>
        <button type="button" id="viewFeedbacks" class="btn">View Feedbacks</button>
    </form>

    <!-- Display Feedbacks -->
    <div id="feedbacksList"></div>
</div>

<script>
    // Handle feedback form submission via AJAX
    $("#feedbackForm").submit(function(e) {
        e.preventDefault(); // Prevent the default form submission

        var feedback = $("#feedback").val();

        $.ajax({
            type: "POST",
            url: "feedbacks.php", // URL of the same page
            data: { 
                submit_feedback: true,
                feedback: feedback
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    $("#message").html('<div class="success-message">' + data.success + '</div>');
                } else if (data.error) {
                    $("#message").html('<div class="error-message">' + data.error + '</div>');
                }
                // Clear the feedback field
                $("#feedback").val('');
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", status, error); // Log AJAX errors
            }
        });
    });

    // Handle fetching feedbacks via AJAX
    $("#viewFeedbacks").click(function() {
        $.ajax({
            type: "POST",
            url: "feedbacks.php",
            data: { 
                view_feedbacks: true
            },
            success: function(response) {
                var data = JSON.parse(response);
                var feedbacksHtml = '';
                if (data.feedbacks.length > 0) {
                    feedbacksHtml += '<h3 class="mt-4">Previous Feedbacks</h3><ul class="list-group">';
                    data.feedbacks.forEach(function(fb) {
                        feedbacksHtml += '<li class="list-group-item"><strong>ID ' + fb.id + ':</strong> ' + fb.feedback + '<br><small class="text-muted">' + fb.created_at + '</small></li>';
                    });
                    feedbacksHtml += '</ul>';
                } else {
                    feedbacksHtml = '<p class="alert alert-warning">No feedbacks available yet.</p>';
                }
                $("#feedbacksList").html(feedbacksHtml);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", status, error); // Log AJAX errors
            }
        });
    });
</script>

</body>
</html>
