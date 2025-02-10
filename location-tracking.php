<?php
session_start();
include('includes/dbconnection.php');

// Fetch hospital location from page description
$sql = "SELECT PageDescription FROM tblpage WHERE PageType='contactus'";
$query = $dbh->prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);

// Use the exact address from the database
$hospitalAddress = '';
if ($result) {
    // Trim and use the exact address from the row
    $hospitalAddress = trim($result->PageDescription);
}

// If no address found, provide a fallback
if (empty($hospitalAddress)) {
    $hospitalAddress = 'AIMS Hospital, India';
}

// Fallback coordinates (will be replaced by geocoding)
$hospitalCoords = [
    'lat' => 0,
    'lng' => 0
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Tracking - AIMS Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #map { height: 600px; margin-top: 15px; }
        .location-form {
            margin-bottom: 20px;
        }
        #errorContainer, #addressDebugContainer {
            margin-top: 10px;
        }
        #routeDetails {
            margin-top: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Find Your Route to AIMS Hospital</h2>
        
        <div id="errorContainer" class="alert alert-danger" style="display:none;"></div>
        <div id="addressDebugContainer" class="alert alert-info">
            Hospital Address: <strong><?php echo htmlspecialchars($hospitalAddress); ?></strong>
        </div>
        
        <div class="card">
            <div class="card-header">
                Location Tracking
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Automatic Location</h5>
                        <button id="autoLocateBtn" class="btn btn-primary mb-3">Use My Current Location</button>
                    </div>
                    <div class="col-md-6">
                        <h5>Manual Location</h5>
                        <form id="manualRouteForm" class="location-form">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="startLocation" placeholder="Enter your starting address" required>
                            </div>
                            <button type="submit" class="btn btn-success">Get Route</button>
                        </form>
                    </div>
                </div>

                <div id="routeDetails" style="display:none;">
                    <h5>Route Information</h5>
                    <p id="routeDistance"></p>
                    <p id="routeDuration"></p>
                </div>

                <div id="map" class="mt-3"></div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const hospitalAddress = "<?php echo addslashes($hospitalAddress); ?>";
        let hospitalCoords = {
            lat: 0,
            lng: 0
        };
        let map; // Global map variable
        const errorContainer = document.getElementById('errorContainer');
        const routeDetailsContainer = document.getElementById('routeDetails');
        const routeDistanceElement = document.getElementById('routeDistance');
        const routeDurationElement = document.getElementById('routeDuration');

        // Function to show error messages
        function showError(message) {
            errorContainer.textContent = message;
            errorContainer.style.display = 'block';
            console.error(message);
        }

        // Geocoding function to convert address to coordinates
        async function geocodeAddress(address) {
            try {
                // Use OpenStreetMap Nominatim for free geocoding
                const response = await axios.get('https://nominatim.openstreetmap.org/search', {
                    params: {
                        q: address,
                        format: 'json',
                        limit: 1
                    }
                });

                if (response.data && response.data.length > 0) {
                    return {
                        lat: parseFloat(response.data[0].lat),
                        lng: parseFloat(response.data[0].lon)
                    };
                } else {
                    showError(`Could not find coordinates for address: ${address}`);
                    return null;
                }
            } catch (error) {
                showError(`Geocoding error: ${error.message}`);
                return null;
            }
        }

        // Clear all route layers from the map
        function clearRoutes() {
            map.eachLayer((layer) => {
                if (layer instanceof L.Marker || layer instanceof L.Polyline) {
                    map.removeLayer(layer);
                }
            });
        }

        // Initialize map and geocode hospital address on page load
        async function initializeMap() {
            // Geocode hospital address
            const coords = await geocodeAddress(hospitalAddress);
            
            if (coords) {
                hospitalCoords = coords;
                
                // Initialize map
                map = L.map('map').setView([hospitalCoords.lat, hospitalCoords.lng], 10);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

                // Hospital marker
                L.marker([hospitalCoords.lat, hospitalCoords.lng])
                    .addTo(map)
                    .bindPopup('AIMS Hospital')
                    .openPopup();

                // Attach event listeners
                attachLocationListeners();
            } else {
                showError('Could not initialize map. Please check the hospital address.');
            }
        }

        // Attach location detection event listeners
        function attachLocationListeners() {
            // Automatic Location Detection
            document.getElementById('autoLocateBtn').addEventListener('click', () => {
                // Clear previous errors and routes
                errorContainer.style.display = 'none';
                routeDetailsContainer.style.display = 'none';
                clearRoutes();

                if ('geolocation' in navigator) {
                    navigator.geolocation.getCurrentPosition(async (position) => {
                        const userCoords = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };

                        // Add user location marker
                        L.marker([userCoords.lat, userCoords.lng])
                            .addTo(map)
                            .bindPopup('Your Location')
                            .openPopup();

                        // Get and display route
                        const routeData = await getRoute(userCoords, hospitalCoords);
                        if (routeData) {
                            const routeLatLngs = routeData.coordinates.map(coord => [coord[1], coord[0]]);
                            L.polyline(routeLatLngs, {color: 'blue'}).addTo(map);
                            
                            // Fit map to route
                            map.fitBounds(routeLatLngs);

                            // Display route details
                            routeDistanceElement.textContent = `Distance: ${(routeData.distance / 1000).toFixed(2)} km`;
                            routeDurationElement.textContent = `Estimated Travel Time: ${Math.round(routeData.duration / 60)} minutes`;
                            routeDetailsContainer.style.display = 'block';
                        }
                    }, (error) => {
                        showError('Error getting location: ' + error.message);
                    });
                } else {
                    showError('Geolocation is not supported by your browser');
                }
            });

            // Manual Route Selection
            document.getElementById('manualRouteForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                // Clear previous errors and routes
                errorContainer.style.display = 'none';
                routeDetailsContainer.style.display = 'none';
                clearRoutes();

                const startLocation = document.getElementById('startLocation').value;

                // Geocode start location
                const startCoords = await geocodeAddress(startLocation);

                if (!startCoords) return;

                // Add markers
                L.marker([startCoords.lat, startCoords.lng])
                    .addTo(map)
                    .bindPopup('Start Location')
                    .openPopup();

                // Get and display route
                const routeData = await getRoute(startCoords, hospitalCoords);
                if (routeData) {
                    const routeLatLngs = routeData.coordinates.map(coord => [coord[1], coord[0]]);
                    L.polyline(routeLatLngs, {color: 'blue'}).addTo(map);
                    
                    // Fit map to route
                    map.fitBounds(routeLatLngs);

                    // Display route details
                    routeDistanceElement.textContent = `Distance: ${(routeData.distance / 1000).toFixed(2)} km`;
                    routeDurationElement.textContent = `Estimated Travel Time: ${Math.round(routeData.duration / 60)} minutes`;
                    routeDetailsContainer.style.display = 'block';
                }
            });
        }

        // Routing function
        async function getRoute(startCoords, endCoords) {
            try {
                // Use OpenStreetMap's OSRM routing service
                const response = await axios.get(`https://router.project-osrm.org/route/v1/driving/${startCoords.lng},${startCoords.lat};${endCoords.lng},${endCoords.lat}`, {
                    params: {
                        overview: 'full',
                        geometries: 'geojson'
                    }
                });

                if (response.data.routes && response.data.routes.length > 0) {
                    const route = response.data.routes[0];
                    return {
                        coordinates: route.geometry.coordinates,
                        distance: route.distance,
                        duration: route.duration
                    };
                } else {
                    showError('Could not calculate route');
                    return null;
                }
            } catch (error) {
                showError(`Routing error: ${error.message}`);
                return null;
            }
        }

        // Initialize map on page load
        initializeMap();
    </script>
</body>
</html>
