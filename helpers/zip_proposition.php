<?php
require_once('Spyc.php');

//La fonction extrait les fichiers supprime l'archive et renvoit un tableau de toutes les informations d'un fichier,
//Si un fichier ne fournit pas toutes les informations, on le précise dans un champs du tableau renvoyé.
//Si il y a eu une erreur on renvoit un numero qui correspond a la condition non respectée
function zip_tmp()
{
	if ($_FILES['mon_fichier']['error'] != 0)
	{
		return 1;
	}
	if(strcmp(strtolower( substr( strrchr( $_FILES['mon_fichier']['name'], '.') ,1) ),"zip")!=0) ;
	{
		return 2;
	}

	$zip = new ZipArchive;

	if ($zip->open($_FILES['mon_fichier']['tmp_name']) === FALSE)
	{
		return 3;
	}
	$zip->ExtractTo("/tmp/");
	$zip->close();        
	if(file_exists('/tmp/config.yml')
	{ 
		return Spyc::YAMLLoad('/tmp/config.yml');
	}
		else
	{
		return 4;
	}
}

function proposition_par_zip()
{
	$resultat_reception = zip_tmp();
	if(!is_array($resultat_reception))
	{
		return $resultat_reception;
	}
	foreach($resultat_reception as $k => $v) // pour toutes les propositions
	{
		if(!is_array($v))
		{
			return null;
		}
		if(!(array_key_exists("Annee",$v) && array_key_exists("Correction",$v) && array_key_exists("Matiere",$v) && array_key_exists("Titre",$v) && array_key_exists("Description",$v) && is_array($v["Description"])))
		{
			return null;
		}
		foreach($v as $cle => $valeur)
		{
			if(!(is_string($valeur) && file_exists("/tmp/$valeur")))
			{
				continue;
			}

			$cleux = substr($valeur,strpos($valeur,".")+1);
			if(!strcmp($cleux,"html") || !strcmp($cleux,"php"))
			{
				move_uploaded_file("/tmp/".$valeur,$valeur.".txt");
			}
			else
			{
				move_uploaded_file("/tmp/".$valeur,$valeur);
			}
		}
		return $resultat_reception;
	}
	return null;
}
?>
