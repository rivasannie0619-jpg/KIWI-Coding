<?php
header("Content-Type: application/json");
require_once 'database.php';
require_once 'class/intern.php';

$database = new Database();
$internship = new Internship($database->conn);

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch($action) {
    case 'fetch':
        echo json_encode($internship->DisplayAll());
        break;

    case 'fetch_single':
        $id = intval($_GET['id'] ?? 0);
        echo json_encode($internship->readOne($id));
        break;

    case 'save':
        $id = $_POST['id'] ?? '';
        
        $data = [
            'full_name'        => $_POST['full_name'] ?? '',
            'student_id'       => $_POST['student_id'] ?? '',
            'university'       => $_POST['university'] ?? '',
            'courses'          => $_POST['courses'] ?? '',
            'start_date'       => $_POST['start_date'] ?? '',
            'end_date'         => $_POST['end_date'] ?? '',
            'department'       => $_POST['department'] ?? '',
            'work_arrangement' => $_POST['work_arrangement'] ?? 'On-site',
            'hours_required'   => intval($_POST['hours_required'] ?? 0),
            'status'           => $_POST['status'] ?? 'Active'
        ];

        if(empty($data['full_name']) || empty($data['student_id']) || empty($data['courses'])) {
            echo json_encode(['status' => 'error', 'message' => 'Please provide all mandatory details.']);
            exit;
        }

        if(empty($id)) {
            if($internship->create($data)) {
                echo json_encode(['status' => 'success', 'message' => 'Intern profile stored successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Save failed. Please check the submitted values.']);
            }
        } else {
            if($internship->update($id, $data)) {
                echo json_encode(['status' => 'success', 'message' => 'Intern profile successfully updated.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Update failed. Please verify the record and try again.']);
            }
        }
        break;

    case 'delete':
        $id = intval($_POST['id'] ?? 0);
        if($internship->delete($id)) {
            echo json_encode(['status' => 'success', 'message' => 'Record successfully dropped.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Delete failed.']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}

?>