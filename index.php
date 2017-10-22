<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload de fichier</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
        $acceptedExtensions = ['png', 'jpg', 'gif'];
        $maxSizeFile = 1000000;

        if (isset($_POST['supress'])){
            if (file_exists('docs/'. $_POST['id'])){
                unlink('docs/'. $_POST['id']);
            }
        }

        if(isset($_POST['chargement']) && !empty($_POST)){
            if (count($_FILES['upload']['name']) > 0){
                for ($i=0; $i<count($_FILES['upload']['name']); $i++){

                    $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                    $extensionFile = pathinfo($_FILES['upload']['name'][$i], PATHINFO_EXTENSION);

                    if (!in_array($extensionFile, $acceptedExtensions)){
                        $error = 'Merci d\'uploader un fichier jpg, png ou gif';
                    }elseif (filesize($_FILES['upload']['tmp_name'][$i]) > $maxSizeFile){
                        $error = 'La taille de votre fichier est supérieur à 1Mo';
                    }elseif (!isset($error)){
                        if ($tmpFilePath != ''){
                            $shortName = 'image' . uniqid() . '.' . $extensionFile;
                            $filePath = 'docs/' . $shortName;

                            if (move_uploaded_file($tmpFilePath, $filePath)){
                                $files[] = $filePath;
                            }
                        }
                    }
                }
            }
        }
    ?>
    <div class="container">
        <div>
            <p class="bg-danger">
                <?php if (isset($error)) echo $error; ?>
            </p>
        </div>

        <form method="post" enctype="multipart/form-data" action="#">
            <div class="form-group">
                <label for="upload">Ajouter un fichier : </label>
                <input type="file" name="upload[]" id="upload" multiple="multiple">
            </div>
            <button type="submit" name="chargement" class="btn btn-primary">Envoyer mon fichier</button>
        </form>

        <div class="row">
            <?php

            $fileList = scandir('docs/');
                foreach ($fileList as $value){
                    if ($value != '.' && $value != '..') { ?>
                        <div class="col-xs-6 col-md-4">
                            <div class="thumbnail">
                                <img src="docs/<?= $value ;?> ">
                                <div class="caption">
                                    <p><?= $value; ?></p>
                                    <form method="post" action="#">
                                        <input type="hidden" name="id" value="<?= $value ; ?>">
                                        <button type="submit" name="supress" class="btn btn-warning">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div><?php
                    }
                }



            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>