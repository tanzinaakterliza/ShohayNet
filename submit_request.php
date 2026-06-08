<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'] ?? 'Unknown';
    $phone = $_POST['phone'] ?? 'N/A';
    $nid = $_POST['nid'] ?? '';
    $family_count = $_POST['family_count'] ?? '';
    $division = $_POST['division'] ?? '';
    $district = $_POST['district'] ?? '';
    $area_gps = $_POST['area_gps'] ?? '';
    

    $emergency_types = "";
    if (isset($_POST['types']) && is_array($_POST['types'])) {
        $emergency_types = implode(", ", $_POST['types']);
    } else {
        $emergency_types = $_POST['emergency_types'] ?? 'N/A';
    }

    $urgency = $_POST['urgency'] ?? 'Medium';
    $description = $_POST['description'] ?? '';

    $stmt = $conn->prepare("INSERT INTO requests (full_name, phone, nid, family_count, division, district, area_gps, emergency_types, urgency, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $full_name, $phone, $nid, $family_count, $division, $district, $area_gps, $emergency_types, $urgency, $description);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>