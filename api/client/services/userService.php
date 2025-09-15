<?php
class UserService
{

    private $db;
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function validateUserLoggedIn()
    {
        $firstName = "";
        $lastName = "";
        $mobile = "";
        $fullName = "";
        $profileImage = "";
        $email = "";
        $address = "";
        $city="";
        $isGoogleUser = false;
        $userId = "";
        if (isset(($_SESSION["client_id"]))) {
            $nameParts = explode(' ', $_SESSION["client_name"], 2);
            $userId = $_SESSION["client_id"];
            $firstName = $nameParts[0];
            $lastName = $nameParts[1];
            $fullName = $_SESSION["client_name"];
            $email = $_SESSION["client_email"];
            $address = $_SESSION["client_address"];
            $city = $_SESSION["client_city"];
            $mobile = $_SESSION["client_mobile"];
            $isGoogleUser = $_SESSION["client_type"] == 'google' && true;
            $profileImage = $_SESSION["client_type"] == 'google' ?  'api/client/proxy.php?url=' . $_SESSION["client_avatar"] : (!empty($_SESSION["client_avatar"])
                ? "uploads/client/profileImages/" . $_SESSION["client_avatar"]
                : "https://ui-avatars.com/api/?name={$firstName}+{$lastName}&size=128");
        } else if (isset($_COOKIE["remember_me"])) {
            try {
                $rememberToken = $_COOKIE["remember_me"];
                $query = "SELECT * FROM user WHERE remember_token IS NOT NULL";
                $stmt = $this->db->prepare($query);
                $stmt->execute();
                $validClient = null;
                $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($clients as $client) {
                    if (password_verify($rememberToken, $client['remember_token'])) {
                        $validClient = $client;
                        break;
                    }
                }
                $userId = $validClient["id"];
                $firstName = $validClient["first_name"];
                $lastName = $validClient["last_name"];
                $fullName = "{$firstName} {$lastName}";
                $email = $validClient["email"];
                $mobile = $validClient["mobile"];
                $address = $validClient["address"];
                $city = $validClient["city"];
                $isGoogleUser = $validClient["auth_providers_provider_id"] == 2 && true;
                $profileImage = $validClient["auth_providers_provider_id"] == 2 ?  'api/client/proxy.php?url=' . $validClient["profile_picture_url"] : (!empty($validClient["profile_picture_url"])
                    ? "uploads/client/profileImages/" . $validClient["profile_picture_url"]
                    : "https://ui-avatars.com/api/?name={$firstName}+{$lastName}&size=128");
    
                if ($validClient) {
                    $_SESSION['client_id'] = $validClient['id'];
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                }
            } catch (\Exception $e) {
                error_log($e->getMessage());
            }
        }else{
            $profileImage = "https://www.gravatar.com/avatar/?d=mp";
        }
        return [
            'userId' => $userId,
            'fname' => $firstName,
            'lname' => $lastName,
            'email' => $email,
            'mobile' => $mobile,
            'fullname' => $fullName,
            'profile' => $profileImage,
            'address' => $address,
            'city' => $city,
            'isGoogleUser' => $isGoogleUser
        ];
    }
    public function __destruct()
    {
        $this->db = null; 
    }
}
