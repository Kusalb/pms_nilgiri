<?php
require_once "../config.php";
session_start();
if(!isset($_SESSION['username']))
{
    header("location: login.php");
    exit;
}
$sql = "SELECT * FROM profile WHERE user_id=".$_SESSION['id'];
$profile=mysqli_query($conn,$sql);
$profile_data = mysqli_fetch_array($profile);

// Define variables and initialize with empty values
$project_title= $project_description = $github_link = "";
$project_title_err = $project_description_err = $github_link_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Validate project title
    $input_project_title = trim($_POST["project_title"]);
    if (empty($input_project_title)) {
        $project_title_err = "Please enter a project title.";
        echo "Please enter a project title";

    } elseif (!filter_var($input_project_title, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $project_title_err = "Please enter a valid project title.";
        echo "Please enter a valid project title.";

    } else {
        $project_title = $input_project_title;
    }

// Validate project description
    $input_project_description = trim($_POST["project_description"]);
    if (empty($input_description)) {
        $input_project_description_err = "Please enter a description.";
        echo "Please enter a description.";
    } else {
        $project_description = $input_project_description;
    }

// Validate github
    $input_github_link = trim($_POST["github_link"]);
    if (empty($input_github_link)) {
        $last_name_err = "Please enter a github link.";
        echo "Please enter a github link.";
    } elseif (!filter_var($input_github_link, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z0-9\s]+$/")))) {
        $last_name_err = "Please enter a valid github link.";
        echo "Please enter a valid github link.";
    } else {
        $github_link = $input_github_link;
    }

    if (empty($project_title_err) && empty($project_description_err) && empty($github_link_err)) {
        $sql = "INSERT INTO projects (project_title, project_description, github_link, profile_id) VALUES (?, ?, ?,?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssi", $project_title, $project_description, $github_link, $profile_id);

            // Set parameters
            $project_title = $_POST['project_title'];
            $project_description = $_POST['project_description'];
            $github_link= $_POST['github_link'];
            $profile_id = $profile_data['id'];

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                header("location: retrieve_to.php");
            } else {
                echo "ERROR: Could not execute query: $sql. " . mysqli_error($conn);
            }
        } else {
            echo "ERROR: Could not prepare query: $sql. " . mysqli_error($conn);
        }

// Close statement
        mysqli_stmt_close($stmt);

// Close connection
        mysqli_close($conn);
    }
}
?>
<?php include "header.php" ?>

    <h2>Create Project</h2>
    <form action="create.php" method="post" enctype="multipart/form-data">
            <input type="text" id="project_title" placeholder="Enter project title" name="project_title"><br>
            <textarea name="project_description"></textarea><br>
            <input type="text" id="github_link" placeholder="Github link" name="github_link"><br>
        <button type="submit" >Submit</button>
    </form>
<?php include "footer.php"?>