
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/logo.png">

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Custom CSS -->
   
</head>
<body>
<?php include '../includes/navbar.php'; ?>
<div class="container mt-4">


    <h1>Welcome to the Accounts</h1>
    <p>Accounts section</p>

    <div class="row mt-4">
        <!-- Add User Form -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header">ADD USER</div>
                <div class="card-body">
                    <form id="userInfoForm">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="mdi mdi-id-card"></i></span>
                            <input type="number" class="form-control" required id="stud_id" placeholder="Student ID">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="mdi mdi-account"></i></span>
                            <input type="text" class="form-control" id="fname" placeholder="First Name">
                            <input type="text" class="form-control" id="lname" placeholder="Last Name">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="mdi mdi-email"></i></span>
                            <input type="email" class="form-control" id="email" placeholder="Email" required>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="mdi mdi-counter"></i></span>
                            <input type="number" class="form-control" id="age" placeholder="Age">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="mdi mdi-marker"></i></span>
                            <input type="text" class="form-control" id="address" placeholder="Address">
                        </div>
                        <div class="input-group mb-3 d-grid">
                            <button class="btn btn-primary" type="button" id="addUserBtn" onclick="addUser()">Add User</button>
                            <button class="btn btn-primary mt-2" type="button" id="updateUserBtn" onclick="updateUser()" style="display: none;">Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- User Table -->
        <div class="col-md-9">
            <div id="liveAlert" class="mb-3"></div>
            <div class="card">
                <div class="card-body">
                    <input type="text" class="form-control mb-3" id="search" placeholder="Search ...">
                    <table id="userTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID Number</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Age</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody id="userTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    let currentRow = null;

    function addUser() {
        let stud_id = $("#stud_id").val();
        let fname = $("#fname").val();
        let lname = $("#lname").val();
        let email = $("#email").val();
        let age = $("#age").val();
        let address = $("#address").val();

        if (stud_id !== '' && fname !== '') {
            let newRow = $(`
                <tr>
                    <td>${stud_id}</td>
                    <td>${fname}</td>
                    <td>${lname}</td>
                    <td>${email}</td>
                    <td>${age}</td>
                    <td>${address}</td>
                    <td>
                        <button class="btn btn-warning btn-sm editBtn"><i class="mdi mdi-pencil"></i></button>
                        <button class="btn btn-danger btn-sm deleteBtn"><i class="mdi mdi-delete"></i></button>
                    </td>
                </tr>
            `);

            $("#userTableBody").append(newRow);
            $("#liveAlert").html('<div class="alert alert-success">Data has been added.</div>');
            resetForm();
        } else {
            $("#liveAlert").html('<div class="alert alert-danger">Student ID and First Name are required.</div>');
        }
    }

    function resetForm() {
        $("#stud_id").val("");
        $("#fname").val("");
        $("#lname").val("");
        $("#email").val("");
        $("#age").val("");
        $("#address").val("");
        $("#addUserBtn").show();
        $("#updateUserBtn").hide();
    }

    function updateUser() {
        if (currentRow) {
            currentRow.find("td").eq(0).text($("#stud_id").val());
            currentRow.find("td").eq(1).text($("#fname").val());
            currentRow.find("td").eq(2).text($("#lname").val());
            currentRow.find("td").eq(3).text($("#email").val());
            currentRow.find("td").eq(4).text($("#age").val());
            currentRow.find("td").eq(5).text($("#address").val());

            $("#liveAlert").html('<div class="alert alert-info">User data updated successfully.</div>');
            resetForm();
            currentRow = null;
        }
    }

    $(document).ready(function () {
        $("#search").on("keyup", function () {
            let value = $(this).val().toLowerCase();
            $("#userTableBody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        $(document).on("click", ".deleteBtn", function () {
            $(this).closest("tr").remove();
            $("#liveAlert").html('<div class="alert alert-warning">User deleted.</div>');
        });

        $(document).on("click", ".editBtn", function () {
            currentRow = $(this).closest("tr");
            let tds = currentRow.find("td");

            $("#stud_id").val(tds.eq(0).text());
            $("#fname").val(tds.eq(1).text());
            $("#lname").val(tds.eq(2).text());
            $("#email").val(tds.eq(3).text());
            $("#age").val(tds.eq(4).text());
            $("#address").val(tds.eq(5).text());

            $("#addUserBtn").hide();
            $("#updateUserBtn").show();
        });
    });
</script>
</body>
</html>
