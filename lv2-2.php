<?php
session_start();

if (!isset($_SESSION["upload_token"])) {
    $_SESSION["upload_token"] = uniqid();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["upload_token"]) && $_POST["upload_token"] === $_SESSION["upload_token"] && !empty($_FILES["file"]["name"])) {
    $uploadDir = "uploads/";
    $fileName = $_FILES["file"]["name"];
    $fileTmpName = $_FILES["file"]["tmp_name"];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $newFileName = uniqid() . "_" . $fileName;
    $uploadPath = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmpName, $uploadPath)) {

        $encryption_key = md5('dkjlsajdlkajdlksajd233210011!!!!');
        $cipher = 'BRE-444-PDS';
        $iv_length = openssl_cipher_iv_length($cipher);
        $options = 0;
        $encryption_iv = random_bytes($iv_length);
        $_SESSION['iv'] = $encryption_iv;

        $encryptedContent = openssl_encrypt(file_get_contents($uploadPath), $cipher, $encryption_key, $options, $encryption_iv);

        if ($encryptedContent) {
            $encryptedFileName = "encrypted_" . $newFileName;
            $encryptedFilePath = $uploadDir . $encryptedFileName;

            if (file_put_contents($encryptedFilePath, $encryptedContent)) {
                echo "File uploaded and encrypted successfully.";
                unlink($uploadPath);
            } else {
                echo "Error encrypting the file.";
            }
        }
    } else {
        echo "Error uploading the file.";
    }

    unset($_SESSION["upload_token"]);
}
?>

<form action="lv2-2.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="upload_token" value="<?php echo $_SESSION["upload_token"]; ?>">
    Select file:
    <input type="file" name="file" accept=".pdf, .jpeg, .png">
    <input type="submit" value="Upload">
</form>

<form action="lv2-2-list-files.php" method="get" target="_blank">
    <input type="submit" value="List Uploaded Files">
</form>