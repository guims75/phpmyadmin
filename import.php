<?php

include './base.php';

if (isset($_FILES['sql']))
  {
    $errs = array(
		  1 => 'Le fichier téléchargé excède la taille de maximal.',
		  3 => 'Le fichier n\'a été que partiellement téléchargé.',
		  4 => 'Aucun fichier n\'a été téléchargé.',
		  5 => 'Un dossier temporaire est manquant.',
		  6 => 'Échec de l\'écriture du fichier sur le disque.',
		  7 => 'Une extension PHP a arrété l\'envoi de fichier. PHP ne propose aucun moyen de déterminer quelle extension est en cause. L\'examen du phpinfo() peut aider.'
		  );
    $errs[2] = $errs[1];

    if (isset($errs[$_FILES['sql']['error']]))
      $err = $errs[$_FILES['sql']['error']];
    else if ($db->multiQuery(file_get_contents($_FILES['sql']['tmp_name'])))
      {
	///TODO complete
	$sql = $db->getMySQLi();
	try
	  {
	    do {
	      if ($result = $sql->store_result())
		{
		  while ($row = $result->fetch_row())
		    {
		      var_dump($row);
		    }
		  $result->free_result();
		}
	    } while($sql->next_result());

	  }
	catch(ErrorException $e)
	  {}
      }
    else
      $err = $db->getError();
  }

include './view/import.php';