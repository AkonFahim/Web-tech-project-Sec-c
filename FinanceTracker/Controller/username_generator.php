<?php
function generateUsername($fullName, $con) {
    $nameParts = explode(' ', trim($fullName));
    $lastName = end($nameParts);
    
    $username = '';
    $lastName = strtolower($lastName);
    for ($i = 0; $i < strlen($lastName); $i++) {
        $char = $lastName[$i];
        if ($char >= 'a' && $char <= 'z') {
            $username .= $char;
        }
    }
    
    if (empty($username)) {
        $username = 'user';
    }
    
    $searchPattern = $username . '%';
    $sql = "SELECT username FROM users WHERE username LIKE '$searchPattern'";
    $result = mysqli_query($con, $sql);
    
    $existingNumbers = [0];
    
    if($result && mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $existingUsername = $row['username'];
            
            if (strpos($existingUsername, $username) === 0) {
                $numberPart = substr($existingUsername, strlen($username));
                
                $isAllDigits = true;
                for ($i = 0; $i < strlen($numberPart); $i++) {
                    if ($numberPart[$i] < '0' || $numberPart[$i] > '9') {
                        $isAllDigits = false;
                        break;
                    }
                }
                
                if ($isAllDigits && !empty($numberPart)) {
                    $existingNumbers[] = (int)$numberPart;
                }
            }
        }
    }
    
    $newNumber = max($existingNumbers) + 1;
    
    if ($newNumber < 10) {
        $numberString = '00' . $newNumber;
    } elseif ($newNumber < 100) {
        $numberString = '0' . $newNumber;
    } else {
        $numberString = (string)$newNumber;
    }
    
    return $username . $numberString;
}
?>