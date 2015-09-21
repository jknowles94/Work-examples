<?php 

$user = ""; //prevent the "no index" error from $_POST
$pass = "";
if (isset($_POST['user'])){ //check for them and set them so
$user = $_POST['user'];
}
if (isset($_POST['pass'])){ //so that they don't return errors
$pass = $_POST['pass'];
}    

$useroptions = ['cost' => 8,]; //al up to you
$pwoptions   = ['cost' => 8,]; //all up to you
$userhash    = password_hash($user, PASSWORD_BCRYPT, $useroptions); //hash entered user
$passhash    = password_hash($pass, PASSWORD_BCRYPT, $pwoptions);  //hash entered pw
$hasheduser  = file_get_contents("user.txt"); //this is our stored user
$hashedpass  = file_get_contents("pass.txt"); //and our stored password


if ((password_verify($user, $hasheduser)) && (password_verify($pass, $hashedpass))) {
	// output headers so that the file is downloaded rather than displayed
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=data.csv');

	// create a file pointer connected to the output stream
	$output = fopen('php://output', 'w');

	// output the column headings
	fputcsv($output, array('id', 'First Name', 'Last Name','Company', 'Address', 'Address - 2','Postcode', 'Email', 'Phone Number', 'Bringing a guest', 'Terms'));

	// fetch the data
	mysql_connect("localhost","aa_db_user","4Merican_airlin3s_dat4entry","american_airlines");
	mysql_select_db('american_airlines');
	$rows = mysql_query('SELECT * FROM posts');

	// loop over the rows, outputting them
	while ($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);

} 
else //if it was invalid it'll just display the form, if there was never a $_POST
{?> 

        <form method="POST" action="download-data.php">
        Username: <input type="text" name="user"></input><br/>
        Password: <input type="password" name="pass"></input><br/>
        <input type="submit" name="submit" value="Go"></input>
        </form>
<?php 
} 


?>