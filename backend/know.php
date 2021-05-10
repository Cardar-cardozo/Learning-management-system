<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require ('vendor/autoload.php');
use \Firebase\JWT\JWT;
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class know{

    public $api  = array('existed' =>'','regis' =>'','Auth' =>'', 'notfound'=>'',  'passworderror'=>'');
    public function __construct(){
        $servername =$_ENV['SERVERNAME'];
        $root = $_ENV['USERNAME'];
        $pword =$_ENV['PASSWORD'];
        $dbname =$_ENV['DBNAME'];
        $secret = $_ENV['SECRET'];
        $this->conn = new mysqli($servername, $root, $pword, $dbname);
        if (!$this->conn) {
            die();
        }else{
           
        }
        
    }


    public function register($firstName, $lastName, $Email, $passwordharsh){

         $existed= "SELECT * FROM student_tb WHERE email = ?";
        $stmt = $this->conn->prepare($existed);
        $stmt->bind_param("s", $Email);
    
            $stmt->execute();
            $check = $stmt->get_result();

        if($check->num_rows>0){
            $api['existed'] = 'email existed';
            // $response = array('fail'=>'exited');
            echo json_encode($api);
            
        } else {
            $stmt = $this->conn->prepare("INSERT INTO student_tb (firstname, lastname, password, email) VALUES (?, ?, ?,?)");
            $stmt->bind_param("ssss", $firstName, $lastName, $passwordharsh, $Email);
            $api['regis'] = 'Successfully Registered';

            // $firstname = $firstName;
            // $lastname = $lastName;
            // $password = $passwordharsh;
            // $email = $Email;
            $stmt->execute();

            echo json_encode($api);

        }




            

            $stmt->close();
            $this->conn->close();
        // $existed= "SELECT * FROM student_tb WHERE email = '$Email'";
        // $check = $this->conn->query($existed);

        // if($check->num_rows>0){
        //     $api['existed'] = 'email existed';
            // $response = array('fail'=>'exited');
        // } else {
        //     $this->conn->query("INSERT INTO student_tb(firstname, lastname, password, email) VALUES ('$firstName','$lastName', '$passwordharsh', '$Email')");
        //     $api['regis'] = 'Successfully Registered';
            // $response = array('sucess'=>' exist');
        // }
        // return $response;

        // echo json_encode ($api);
    }


    public function login($Email, $Password){
        $user = "SELECT * FROM student_tb WHERE email = ?";
        $stmt = $this->conn->prepare($user);
        $stmt->bind_param("s", $Email);
    
            $stmt->execute();
            $checkemail = $stmt->get_result();
     

        if ($checkemail->num_rows>0) {
            $fetchuser = $checkemail->fetch_assoc();
            $fetchpass = $fetchuser['password'];
            $fetchuserid = $fetchuser['student_id'];
            $fetchemail = $fetchuser['email'];
        
            $verify = password_verify($Password, $fetchpass);
            if ($verify) {
                $data = [
                    'iss'=>'localhost/4200',
                    'iat'=>time(),
                    'exp'=>time()* 3600,
                    'user'=>$fetchuserid
                ];
                $auth = JWT::encode($data, $_ENV['SECRET']);
                $api['Auth'] = json_encode($auth);

                echo json_encode($api);
            } else{
                $api['passworderror'] = 'password incorrect';
                echo json_encode($api);
            }
        }
        else {
            $api['notfound'] = 'email not found';
            echo json_encode($api);
        }
    }

    public function decode($token){
        // echo 'success';
        // echo $_ENV['SECRET'];
        // echo json_encode($token->auth);

        $decoded = JWT::decode($token->auth,$_ENV['SECRET'],array('HS256'));
        $userid = $decoded->user;

        $checkuser = "SELECT * FROM student_tb WHERE student_id = ?";
       
        $stmt = $this->conn->prepare($checkuser);
        // echo json_encode($stmt);
        // return;
        $stmt->bind_param("i",$userid);
        $stmt->execute();
        $auth = $stmt->get_result();
        if ($auth->num_rows>0) {

            $myFetcheduser = $auth->fetch_assoc();
                
            // $profile = $myFetcheduser['image'];
            $first = $myFetcheduser['firstname'];
            // $amount =  $myFetcheduser['balance'];
            $emai =  $myFetcheduser['email'];
            $lastname = $myFetcheduser['lastname'];

            
            // $api['image'] = $profile;
            $api['firstname'] = $first;
            // $api['amount'] = $amount;
            $api['email'] = $emai;
            $api['lastname'] = $lastname;

            echo json_encode($api);

        }   
        // echo json_encode($check);

        // $gg = json_encode($decoded);
        // return $gg;
        
    }

    public function email($email){

        // echo $email;

        $code = rand();

        $mail = new PHPMailer(true);
   try {
   //Server settings
   $mail->SMTPDebug = SMTP::DEBUG_SERVER;                     
   $mail->isSMTP();                                           
   $mail->Host       = 'smtp.gmail.com';                     
   $mail->SMTPAuth   = true;                               
   $mail->Username   = 'thriftappng@gmail.com';                    
   $mail->Password   = 'Libertycity2020$';                              
   $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
   $mail->Port       = 465;                                   
   $mail->SMTPOptions = array(
   'ssl' => array(
   'verify_peer' => false,
   'verify_peer_name' => false,
   'allow_self_signed' => true
   )
   );
   $mail->SMTPDebug = 0;
   //Recipients
   $mail->setFrom('thriftappng@gmail.com', 'Knowmore');
   $mail->addAddress('techt5562@gmail.com');    
   $mail->addAddress('techt5562@gmail.com');             
   
   $mail->isHTML(true);                                  
   $mail->Subject = 'Confirmation code';
   $mail->Body    = $code;
   $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
   
   $mail->send();
   } catch (Exception $e) {
   echo 'error';
   }

    }


    
}

?>