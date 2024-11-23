<?php

function connect_db() {
    $servername = "localhost"; //Change to your server
    $username = "starostin_Bears"; //Change to your username
    $password = "Admin123*"; //Change to your password
    $dbname = "starostin_Bears"; //Change to your db name

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}

function sanitize_input($data, $conn) {
    $data = $conn->real_escape_string($data);
    return $data;
}


function getCoachInfo($coachId, $conn) {
    $sql = "SELECT first_name, last_name, patronymic, phone FROM coaches WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $coachId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getAthleteAvailability($coachId, $conn) {
    $sql = "SELECT a.id AS athlete_id, a.first_name, a.last_name, aa.date, aa.time_interval
            FROM athletes a
            JOIN athlete_availability aa ON a.id = aa.athlete_id
            LEFT JOIN coach_athlete_assignments caa ON a.id = caa.athlete_id AND caa.date = aa.date AND caa.coach_id = ?
            WHERE caa.coach_id IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $coachId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function assignAthlete($conn, $coachId, $athleteId, $date) {
    $sql = "INSERT INTO coach_athlete_assignments (coach_id, athlete_id, date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        return false;
    }
    $stmt->bind_param("iis", $coachId, $athleteId, $date);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function getAssignedAthletes($coachId, $conn){
  $sql = "SELECT DISTINCT a.first_name, a.last_name, caa.date, aa.time_interval
          FROM athletes a
          JOIN coach_athlete_assignments caa ON a.id = caa.athlete_id
          JOIN athlete_availability aa ON a.id = aa.athlete_id AND caa.date = aa.date
          WHERE caa.coach_id = ?
          GROUP BY a.first_name, a.last_name, caa.date, aa.time_interval"; // DISTINCT and GROUP BY added
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $coachId);
  $stmt->execute();
  $result = $stmt->get_result();
  return $result;
}

// Function to delete a coach assignment
function deleteCoachAssignment($conn, $assignmentId) {
    $sql = "DELETE FROM coach_athlete_assignments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $assignmentId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    } else {
        return false;
    }
}


// Function to delete an athlete availability entry
function deleteAthleteAvailability($conn, $availabilityId) {
    $sql = "DELETE FROM athlete_availability WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $availabilityId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    } else {
        return false;
    }
}

// Function to update an athlete availability entry
function updateAthleteAvailability($conn, $availabilityId, $date, $timeInterval) {
    $sql = "UPDATE athlete_availability SET date = ?, time_interval = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssi", $date, $timeInterval, $availabilityId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    } else {
        return false;
    }
}
?>