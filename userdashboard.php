<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=conference', 'root', ''); // Update with your DB credentials

$user_id = $_SESSION['user_id']; // Logged-in user ID

// Fetch user data including QR code name
$stmt = $pdo->prepare("SELECT name, email, track, phone, qr_code_picture FROM users WHERE participant_id = :id");
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT); // Ensure the parameter is explicitly bound as an integer
$stmt->execute();

$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user_data) {
    echo "Error: User data not found.";
    exit();
}

$user_name = $user_data['name'];
$user_email = $user_data['email'];
$user_track = $user_data['track'];
$user_phone = $user_data['phone'];
$qr_code_name = $user_data['qr_code_picture'] ?? ''; // Default to empty if not found


// Check if the QR code exists
$uploads_dir = "uploads/";  // Directory where QR codes are stored
$qr_code_path = $uploads_dir . $qr_code_name; // Full file path

// Check if the QR code file exists before displaying
if (!empty($qr_code_name) && file_exists($qr_code_path)) {
    $qr_code_display = $qr_code_path;
} else {
    $qr_code_display = ''; // If the file does not exist, set to an empty string
}

?>


<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>International Research Conference of ITUM - 2024</title>

        <!-- CSS FILES -->        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;400;700&display=swap" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-icons.css" rel="stylesheet">
        <link href="css/conference.css" rel="stylesheet">

        <script>
            function downloadQRCode() {
                const qrCanvas = document.getElementById('qrCodeCanvas');
                const link = document.createElement('a');
                link.download = 'qr-code.png';
                link.href = qrCanvas.toDataURL('image/png');
                link.click();
            }

        
        </script>
    </head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="row">
                
                <div class="col-12 d-flex justify-content-between">
                    <h7 class="mb-0">
                      <strong class="text-dark">IRC 2024</strong>
                    </h7>
                    <p class="mb-0">
                        <img src="images/Untitled1.png" alt="ITUM Logo" class="me-2" style="height: 40px;">
                      
                    </p>
                  </div>
            </div>
        </div>
    </header>
    <div class="container-fluid">
        <div class="row">
            <!-- Left Panel -->
            <div class="col-lg-4 left-panel">
                <div class="text-center">
                    <h2>WELCOME</h2><br>
                    <h2><?php echo ucwords(strtolower($user_name)); ?></h2> <!-- Display user name dynamically -->
                    <div class="photo-container position-relative">
                         <img src="images/878685_user_512x512.png" alt="User Photo" class="user-photo mb-3">
                    </div>

                </div>
                <div class="mt-5">
                    <ul class="list-unstyled">
                        <li class="dark-font mb-3"><i class="bi bi-person me-3"></i><?php echo ucwords(strtolower($user_name)); ?></li> <!-- Space added with 'me-2' -->
                        <li class="dark-font mb-3"><i class="bi bi-envelope me-3"></i><?php echo $user_email; ?></li> <!-- Space added with 'me-2' -->
                        <li class="dark-font mb-3"><i class="bi bi-phone me-3"></i><?php echo $user_phone; ?></li> <!-- Space added with 'me-2' -->
                        <li class="dark-font mb-3"><i class="bi bi-clipboard-check me-3"></i><?php echo $user_track; ?></li> <!-- Space added with 'me-2' -->
                    </ul>
                </div>


                <div class="dashboard-section3 qr-section">
    <div class="section-header"><h6>QR Code</h6></div>
    <?php if (!empty($qr_code_name)): ?>
    <img id="qrCodeImage" src="<?php echo $qr_code_name; ?>" alt="QR Code" style="max-width: 100%; height: auto;">
    <div class="qr-actions">
        <a href="<?php echo $qr_code_name; ?>" download="qr_code.png" class="btn custom-btn d-lg-block d-none">Download QR Code</a>
        <p>Provide the QR at the Entrance</p>
    </div>
<?php else: ?>
    <p>QR Code not available.</p>
<?php endif; ?>

</div>

</div>

    
            <!-- Right Panel -->
            <div class="col-lg-8 right-panel">
                <div class="container mt-5">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="dashboard-section1">
                                <div class="section-header">Conference Session Details</div>
                                <h6>Speeches</h6>
                                <ol>
                                    <li>Super-Smart Textiles: A Cross-Disciplinary Journey Towards Future Wearable Technologies</li>
                                    <li>Multiple Intelligences and Sustainability in the Engineering Industry</li>
                                </ol>
                                <!-- Technical Session Tabs -->
                                <nav class="d-flex justify-content-center" style="padding: 5px 0;">
                                    <div class="nav nav-tabs align-items-baseline justify-content-center" id="nav-tab" role="tablist" style="padding: 5px 0;">
                                        <button class="nav-link active" id="nav-SessionA-tab" data-bs-toggle="tab" data-bs-target="#nav-SessionA" type="button" role="tab" aria-controls="nav-SessionA" aria-selected="true" style="font-size: 0.8rem; padding: 5px 10px;">
                                            <h6 style="font-size: 1rem; margin-bottom: 5px;">Technical Session A</h6>
                                        </button>

                                        <button class="nav-link" id="nav-SessionB-tab" data-bs-toggle="tab" data-bs-target="#nav-SessionB" type="button" role="tab" aria-controls="nav-SessionB" aria-selected="false" style="font-size: 0.8rem; padding: 5px 10px;">
                                            <h6 style="font-size: 1rem; margin-bottom: 5px;">Technical Session B</h6>
                                        </button>

                                        <button class="nav-link" id="nav-SessionC-tab" data-bs-toggle="tab" data-bs-target="#nav-SessionC" type="button" role="tab" aria-controls="nav-SessionC" aria-selected="false" style="font-size: 0.8rem; padding: 5px 10px;">
                                            <h6 style="font-size: 1rem; margin-bottom: 5px;">Technical Session C</h6>
                                        </button>

                                        <button class="nav-link" id="nav-SessionD-tab" data-bs-toggle="tab" data-bs-target="#nav-SessionD" type="button" role="tab" aria-controls="nav-SessionD" aria-selected="false" style="font-size: 0.8rem; padding: 5px 10px;">
                                            <h6 style="font-size: 1rem; margin-bottom: 5px;">Technical Session D</h6>
                                        </button>

                                        <button class="nav-link" id="nav-SessionE-tab" data-bs-toggle="tab" data-bs-target="#nav-SessionE" type="button" role="tab" aria-controls="nav-SessionE" aria-selected="false" style="font-size: 0.8rem; padding: 5px 10px;">
                                            <h6 style="font-size: 1rem; margin-bottom: 5px;">Technical Session E</h6>
                                        </button>
                                    </div>
                                </nav>

                                <!-- Tab Content -->
                                <div class="tab-content" id="nav-tabContent" style="padding: 10px; font-size: 0.9rem;">
                                    <!-- Technical Session A -->
                                    <div class="tab-pane fade show active" id="nav-SessionA" role="tabpanel" aria-labelledby="nav-SessionA-tab">
                                        <h6 style="font-size: 1rem; margin-bottom: 5px;">Technical Session A</h6>
                                        <ol style="margin: 0; padding-left: 20px;">
                                            <li style="margin-bottom: 5px;">The Impact of Global Crude Oil Price Fluctuations on the Economy of Sri Lanka with a Special Reference to the Tourism and Agricultural Sectors</li>
                                            <li style="margin-bottom: 5px;">Transforming Traditional Pedagogies: The Impact of OneNote on University Teaching and Learning Practices</li>
                                            <li style="margin-bottom: 5px;">Integrating Solar Photovoltaic Systems for Energy Management: A Case Study of a Higher Educational Institute</li>
                                            <li style="margin-bottom: 5px;">Incorporating Recovered Carbon Black into Solid Tyre Tread Compounds</li>
                                            <li style="margin-bottom: 5px;">Development of Fire-Resistant Behaviour in Natural Rubber Foam Vulcanizates Using Sustainable Materials</li>
                                        </ol>
                                    </div>

                                    <!-- Technical Session B -->
                                    <div class="tab-pane fade" id="nav-SessionB" role="tabpanel" aria-labelledby="nav-SessionB-tab">
                                        <h6 style="font-size: 1rem; margin-bottom: 5px;">Technical Session B</h6>
                                        <ol style="margin: 0; padding-left: 20px;">
                                            <li style="margin-bottom: 5px;">Enhancing Renewable Energy Capacity Through Pumped Storage Systems: A Case Study</li>
                                            <li style="margin-bottom: 5px;">Geothermal Energy Resources for Electricity Generation in Sri Lanka: A Critical Review of Current Status and Prospects</li>
                                            <li style="margin-bottom: 5px;">Bisphenol-A Based Shape Memory Polymer for Soft Robotic Gripper Applications</li>
                                            <li style="margin-bottom: 5px;">A Comparison of Four Ground Electrical Resistivity Survey (GERS) Array Methods Used in Investigating Injected Grouting: A Case Study of Thissa Dam in Sri Lanka</li>
                                        </ol>
                                    </div>

                                    <!-- Technical Session C -->
                                    <div class="tab-pane fade" id="nav-SessionC" role="tabpanel" aria-labelledby="nav-SessionC-tab">
                                        <h6 style="font-size: 1rem; margin-bottom: 5px;">Technical Session C</h6>
                                        <ol style="margin: 0; padding-left: 20px;">
                                            <li style="margin-bottom: 5px;">Machine Learning Approaches in In-Silico Drug Design and Development: A Comprehensive Review</li>
                                            <li style="margin-bottom: 5px;">Efficient Glove Extraction Automation System</li>
                                            <li style="margin-bottom: 5px;">Enhancing Vehicle Connectivity: Li-Fi Technology for Vehicular Communication</li>
                                            <li style="margin-bottom: 5px;">An Integrated Method to Ensuring Safety in Gas Storage Facilities</li>
                                        </ol>
                                    </div>

                                    <!-- Technical Session D -->
                                    <div class="tab-pane fade" id="nav-SessionD" role="tabpanel" aria-labelledby="nav-SessionD-tab">
                                        <h6 style="font-size: 1rem; margin-bottom: 5px;">Technical Session D</h6>
                                        <ol style="margin: 0; padding-left: 20px;">
                                            <li style="margin-bottom: 5px;">Cognitutor: A Sustainable Solution for Improving Metacognitive Skills</li>
                                            <li style="margin-bottom: 5px;">A Predictive Analysis of Student Dropouts in IT Higher Education Programmes</li>
                                            <li style="margin-bottom: 5px;">The Impact of Game-Based Learning on Vocabulary Development Among ESL Undergraduates</li>
                                            <li style="margin-bottom: 5px;">The Impact of Social Anxiety, Learning Orientation, and Learner Autonomy on Second Language Learner Engagement at Tertiary Level</li>
                                        </ol>
                                    </div>

                                    <!-- Technical Session E -->
                                    <div class="tab-pane fade" id="nav-SessionE" role="tabpanel" aria-labelledby="nav-SessionE-tab">
                                        <h6 style="font-size: 1rem; margin-bottom: 5px;">Technical Session E</h6>
                                        <ol style="margin: 0; padding-left: 20px;">
                                            <li style="margin-bottom: 5px;">Integrating Sohrai and Khovar Tribal Art Motifs from Jharkhand, India into Sri Lankan Batik Home Furnishings</li>
                                            <li style="margin-bottom: 5px;">Cultivating Gratitude in an Academic Setting Through a Virtual Platform</li>
                                            <li style="margin-bottom: 5px;">Code-Switching as a Pedagogical Tool: A Case Study in a Technological Institution</li>
                                            <li style="margin-bottom: 5px;">A Comparison of Nutritional Knowledge and Dietary Habits of Under 15 and 17 Badminton Players of Selected Schools in Southern and Western Province</li>
                                        </ol>
                                    </div>
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
                                </div>

                                    

                                


                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="dashboard-section2">
                    <div class="section-header">Session Details</div>
                    <div class="table-responsive" style="max-width: 100%; font-size: 0.8rem;">
                    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Inline CSS */
        .container {
            margin-top: 20px;
        }
        .nav-tabs {
            border-bottom: 1px solid #ddd;
        }
        .nav-tabs .nav-link {
            font-size: 0.9rem;
            padding: 8px 15px;
        }
        .nav-tabs .nav-link.active {
            background-color: #f8f9fa;
            border-color: #ddd;
        }
        .tab-content {
            font-size: 0.85rem;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .table th, .table td {
            padding: 8px 15px;
            text-align: center;
        }
        .table-bordered {
            border: 1px solid #ddd;
        }
        .table-sm th, .table-sm td {
            padding: 6px 12px;
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Tab Navigation -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="speeches-tab" data-bs-toggle="tab" href="#speeches" role="tab" aria-controls="speeches" aria-selected="true">Speeches</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="session-a-tab" data-bs-toggle="tab" href="#session-a" role="tab" aria-controls="session-a" aria-selected="false">Session A</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="session-b-tab" data-bs-toggle="tab" href="#session-b" role="tab" aria-controls="session-b" aria-selected="false">Session B</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="session-c-tab" data-bs-toggle="tab" href="#session-c" role="tab" aria-controls="session-c" aria-selected="false">Session C</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="session-d-tab" data-bs-toggle="tab" href="#session-d" role="tab" aria-controls="session-d" aria-selected="false">Session D</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="session-e-tab" data-bs-toggle="tab" href="#session-e" role="tab" aria-controls="session-e" aria-selected="false">Session E</a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3" id="myTabContent">
        <!-- Speeches Tab -->
        <div class="tab-pane fade show active" id="speeches" role="tabpanel" aria-labelledby="speeches-tab">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Number</th>
                            <th>Authors</th>
                            <th>Venue</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2">Speeches</td>
                            <td>1</td>
                            <td>Dr. Ishara Dharmasena</td>
                            <td>Dr. T.A.G. Gunasekara Multifunctional Hall</td>
                            <td>9:40 am - 10:10 am</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Assoc. Prof. W.B.M. Thoradeniya</td>
                            <td>Dr. T.A.G. Gunasekara Multifunctional Hall</td>
                            <td>10:20 am - 10:50 am</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Session A Tab -->
<div class="tab-pane fade" id="session-a" role="tabpanel" aria-labelledby="session-a-tab">
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Number</th>
                    <th>Authors</th>
                    <th>Venue</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="5">Session A</td>
                    <td>01</td>
                    <td>D.A.D. Lavindika, S.C. Mathugama, D.R.T. Jayasundara</td>
                    <td rowspan="5">D1711</td>
                    <td rowspan="5">2:00 pm - 4:00 pm</td>
                </tr>
                <tr>
                    <td>02</td>
                    <td>J.M.P. Gunasekara, M.D.G.M. Gamage, U.U. Sanjeewani</td>
                </tr>
                <tr>
                    <td>03</td>
                    <td>G.K. Jayatunga, M. Wickramathilaka</td>
                </tr>
                <tr>
                    <td>04</td>
                    <td>D.T.J. Jayawardhana, A.D. Weerakoon, S.G.J. Perera</td>
                </tr>
                <tr>
                    <td>05</td>
                    <td>W.R.R. Chamodani, J.C. Jayawarna, A.D. Weerakoon</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Session B Tab -->
<div class="tab-pane fade" id="session-b" role="tabpanel" aria-labelledby="session-b-tab">
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Number</th>
                    <th>Authors</th>
                    <th>Venue</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="5">Session B</td>
                    <td>01</td>
                    <td>H.M.H.N. Bandaranayake, T. Bambaravanage, K.G.C.J. Senarathna, D.N. Thalagala, L.U. Bhagya, and H.M.C.L. Bandara</td>
                    <td rowspan="5">D1720</td>
                    <td rowspan="5">2:00 pm - 4:00 pm</td>
                </tr>
                <tr>
                    <td>02</td>
                    <td>D.L.S. Hansanie and T. Bambaravanage</td>
                </tr>
                <tr>
                    <td>03</td>
                    <td>S. Jayalath, M. Herath, J. Epaarachchi, and S. Patel</td>
                </tr>
                <tr>
                    <td>04</td>
                    <td>M.D.J.P. Wickramasooriya</td>
                </tr>
               
            </tbody>
        </table>
    </div>
</div>

<!-- Session C Tab -->
<div class="tab-pane fade" id="session-c" role="tabpanel" aria-labelledby="session-c-tab">
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Number</th>
                    <th>Authors</th>
                    <th>Venue</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="5">Session C</td>
                    <td>01</td>
                    <td>M.D.A.S. Manchanayake, B.M.W.P.K. Amarasinghe, and G.K. Jayatunga</td>
                    <td rowspan="5">D1730</td>
                    <td rowspan="5">2:00 pm - 4:00 pm</td>
                </tr>
                <tr>
                    <td>02</td>
                    <td>P.L.L. Arunodhi and H.D.S. Vishwa</td>
                </tr>
                <tr>
                    <td>03</td>
                    <td>U.U.C.D. Chandrasena, D.T.D. Weerathunga and J.C. Jayawarna</td>
                </tr>
                <tr>
                    <td>04</td>
                    <td>W.W.Y. Sanjana and D. Dahanayake</td>
                </tr>
               
            </tbody>
        </table>
    </div>
</div>

<!-- Session D Tab -->
<div class="tab-pane fade" id="session-d" role="tabpanel" aria-labelledby="session-d-tab">
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Number</th>
                    <th>Authors</th>
                    <th>Venue</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="5">Session D</td>
                    <td>01</td>
                    <td>S. J. Hettiarachchi</td>
                    <td rowspan="5">D1740</td>
                    <td rowspan="5">2:00 pm - 4:00 pm</td>
                </tr>
                <tr>
                    <td>02</td>
                    <td>S.M. Mahagama and N.T. Jayatilake</td>
                </tr>
                <tr>
                    <td>03</td>
                    <td>O.K.D.C. Nadeeshan, P.D.I.S. Polwaththa, L.R.S. Mendis, G.R.C.S. Dayawansa, G.H.D. Perera, and S.S. Morapitiya</td>
                </tr>
                <tr>
                    <td>04</td>
                    <td>P.H.Y.C. Priyamantha, D.D. Karunarathne, M.G.B. Chamod, I.D.S. Sandeep, G.S. Diluksi, and S. S. Morapitiya</td>
                </tr>
               
            </tbody>
        </table>
    </div>
</div>

<!-- Session E Tab -->
<div class="tab-pane fade" id="session-e" role="tabpanel" aria-labelledby="session-e-tab">
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Number</th>
                    <th>Authors</th>
                    <th>Venue</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="5">Session E</td>
                    <td>01</td>
                    <td>A.M.T.N. Adasuriya and K. Galappaththi</td>
                    <td rowspan="5">D1750</td>
                    <td rowspan="5">2:00 pm - 4:00 pm</td>
                </tr>
                <tr>
                    <td>02</td>
                    <td>D.S. Kuruppu, K. Galappaththi, G.M.C. Prabhashwara, M.G. Nayanajith, W.L. Gimhan, and N.S. Madanayaka</td>
                </tr>
                <tr>
                    <td>03</td>
                    <td>C. I. Perera</td>
                </tr>
                <tr>
                    <td>04</td>
                    <td>W.S. Kodippili</td>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <footer class="site-footer"> 
        <div class="container">
            <div class="row">

                <div class="col-lg-3 col-12 mt-5">
                    <img src="images/MicrosoftTeams-image.png" alt="ITUM Logo" class="me-2" style="height: 40px;">
                    <p class="copyright-text">Institute of Technology <br> University of Moratuwa</p>
                </div>

               
            </div>
        </div>     
        
          
       
    </footer>
</body>
</html>
