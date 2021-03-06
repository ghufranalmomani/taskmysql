<?php
session_start();
include_once"connection.php";



if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $signUpEmail= $_POST["SignUpEmail"];
    $mobile= $_POST["mobileNumber"];
    $fullName= $_POST["fullName"];
    $SignUpPassword= $_POST["SignUpPassword"];
    $ConfirmSignUpPassword= $_POST["ConfirmSignUpPassword"];
    $birthDate= $_POST["birthDate"];


    $email_pattern = "/([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/";
    $password_pattern = "/(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-])/";
    if(!empty($birthDate)){
        $today = date("Y-m-d");
        $diff = date_diff(date_create($birthDate), date_create($today));
    }

    $sql1 = "SELECT * FROM users_data;";
    $result = mysqli_query($conn,$sql1);
    $resultcheck = mysqli_num_rows($result);
    if($resultcheck > 0)
    {
    while($row = mysqli_fetch_assoc($result))
    {
        if($row["email"] == $signUpEmail){
            $alreadyReg= true;
            break;
        }else{
            $alreadyReg= false;
        }
    }
    }





    if(empty($signUpEmail)){
        $signUpEmailReq= "<span style='color: red;'> Please enter your email </span>";
        echo $signUpEmailReq;
    }else if(!preg_match($email_pattern, $signUpEmail)){
        echo "<span style='color: red;'> The email should follow that pattern: ghufran@gmail.com </span>";
    }

    else if(empty($mobile)){
        $mobileReq= "<span style='color: red;'> Please enter your mobile number </span>";
        echo $mobileReq;
    }else if(preg_match("/[^0-9]/", $mobile)){
        echo "<span style='color: red;'> The mobile number can contain only numbers </span>";
    }else if(strlen($mobile) != 14){
        echo "<span style='color: red;'> The mobile number should contain 14 digits </span>";
    }

    else if(empty($fullName)){
        $fullNameReq= "<span style='color: red;'> Please enter your full name </span>";
        echo $fullNameReq;
    }else if(!preg_match("/(([A-Za-z])\s)/", $fullName)){
        echo "<span style='color: red;'> Full name should contain only letters </span>";
    }else if(str_word_count($fullName) != 4){
        echo "<span style='color: red;'> Full name should be of 4 sections, ex:Ghufran Ra'fat Mohammad Almomani </span>";
    }

    else if(empty($SignUpPassword)){
        $SignUpPasswordReq= "<span style='color: red;'> Please enter a password </span>";
        echo $SignUpPasswordReq;
    }else if(strlen($SignUpPassword) < 8){
        echo "<span style='color: red;'> Password should contain at least 8 characters </span>";
    }else if(!preg_match($password_pattern, $SignUpPassword)){
        echo "<span style='color: red;'> The password should contain uppercase and lowercase letters, numbers, and special characters </span>";
    }else if(preg_match("/\s/", $SignUpPassword)){
        echo "<span style='color: red;'> The password cannot contain spaces </span>";
    }

    else if(empty($ConfirmSignUpPassword)){
        $ConfirmSignUpPasswordReq= "<span style='color: red;'> Please confirm the password </span>";
        echo $ConfirmSignUpPasswordReq;
    }else if($ConfirmSignUpPassword != $SignUpPassword){
        echo "<span style='color: red;'> The two passwords do not match </span>";
    }

    else if(empty($birthDate)){
        $birthDateReq= "<span style='color: red;'> Please enter your birth date </span>";
        echo $birthDateReq;
    }else if($diff->format('%y') < 16){
        echo "<span style='color: red;'> Your age is less than 16, you cannot register </span>";
    }

    else{
        
    $sql= " INSERT INTO users_data (email, mobile , username, passwordd, birth_date)
        VALUES ('$signUpEmail','$mobile','$fullName', '$SignUpPassword', '$birthDate'); ";

        if(mysqli_query($conn , $sql)){
        echo 'new record created sucessfuly ';
        }else{
        echo "error:".$sql."<br>".mysqli_error($conn);
        }
 
        mysqli_close($conn);
        
        header('Location: ../Login Page/index.php');

    if(!empty($signUpEmail)) $x1= $signUpEmail;
    if(!empty($mobile)) $x2= $mobile;
    if(!empty($fullName)) $x3= $fullName;
    if(!empty($birthDate)) $x4= $birthDate;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body{
            text-align: center;
            font-size: 20px;
            font-family: 'Josefin Sans', sans-serif;
        }
        div{padding: 1%;}
        h1{margin: 1%;}
        #p1{margin: 1%;}
        label{
            margin-top: 1%;
        }
        #SignUpEmailLabel{
            position: relative;
            left: -11%;
        }
        #MobileLabel{
            position: relative;
            left: -11%;
        }
        #fullNameLabel{
            position: relative;
            left: -10%;
        }
        #SignUpPasswordLabel{
            position: relative;
            left: -10%;
        }
        #ConfirmSignUpPasswordLabel{
            position: relative;
            left: -8%;
        }
        #birthDateLabel{
            position: relative;
            left: -9%;
        }
        input{
            width: 25%;
            height: 5vh;
            margin-bottom: 1%;
            font-size: 20px;
            font-family: 'Josefin Sans', sans-serif;
        }
        input::placeholder {
            font-size: 18px;
            font-family: 'Josefin Sans', sans-serif;
        }
        button{
            width: 25%;
            height: 6vh;
            border-radius: 25px;
            margin-top: 1%;
            border: solid rgb(137, 137, 240) 1px;
            background-color:rgb(137, 137, 240);
            color: white;
            font-size: 20px;
            font-family: 'Josefin Sans', sans-serif;
        }
        a{
            text-decoration: none;
            font-weight: bold;
            color: black;
        }
    </style>
</head>
<body>
    <div>
        <h1>Sign Up</h1>
        <p id="p1">Create an Account, it's free</p>
        <form action="" method="post">
            <label for="SignUpEmail" id="SignUpEmailLabel">Email</label> <br>
            <input type="text" id="SignUpEmail" name="SignUpEmail" placeholder="ghufran@gmail.com" 
            value="<?php if(isset($x1)) echo $x1;?>"> <br>
         
            <label for="mobileNumber" id="MobileLabel">Mobile</label> <br>
            <input type="tel" id="mobileNumber" name="mobileNumber" value="<?php if(isset($x2)) echo $x2;?>"> <br>

            <label for="fullName" id="fullNameLabel">Full Name</label> <br>
            <input type="text" id="fullName" name="fullName"placeholder="ex: Ghufran Ra'fat Mohammad Almomani" value="<?php if(isset($x3)) echo $x3;?>"> <br>
            <label for="SignUpPassword" id="SignUpPasswordLabel">Password</label> <br>
            <input type="password" id="SignUpPassword" name="SignUpPassword"> <br>

            <label for="ConfirmSignUpPassword" id="ConfirmSignUpPasswordLabel">Confirm Password</label> <br>
            <input type="password" id="ConfirmSignUpPassword" name="ConfirmSignUpPassword"> <br>

            <label for="birthDate" id="birthDateLabel">Date of Birth</label> <br>
            <input type="date" id="birthDate" name="birthDate" value="<?php if(isset($x4)) echo $x4;?>"> <br>

            <button type="submit">Sign Up</button> <br>
        </form>
        <p id="p2">Already have an account?<a href="../Login Page/index.php">Login</a></p>
    </div>
</body>
</html>