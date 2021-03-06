<?php
	require 'pirox.class.php';
	
	if ( isset( $_GET['app']) && strlen( $_GET['app']) ==32 ) // "app" Désigne une utilisation via le client uniquement
	{
		$token = htmlspecialchars( $_GET['app']);
		$insc = new Pirox( 'pirox', 'root', ''); // Création d'une instance ($DB_NAME/$DBB_USER/$DB_PASS)
		$resp = $insc->dbGetData( $token);
		if ( empty( $resp)){
			$stoken = $insc->getKeyServer();
			if ( $stoken != '[__FALSE]'){
				$A = $insc->getSubA();
				if ( $A == 0 ){ $A += 54321054321; }
				$B = $insc->getSubB();
				if ( $B == 0 ){ $B += 54321054321; }
				$E = $insc->getSubE();
				if ( $E == 0 ){ $E += 54321054321; }
				$P = $insc->getSubP();
				if ( $P == 0 ){ $P += 54321054321; }
				/* -----
				Pouvons nous faire un UPDATE ? 
				----- */
				$appSum = ($A + $B + $E + $P)-(54321054321 *4); // Check de la somme des sub.
				if ( $appSum <= 0){ 							// Si la somme est <= 0
					$insc->updateValidity(0);					// Update de la validité dans la class
					$insc->dbUpdateData();						// Puis sauvegarde dans la base de donnée
					echo $insc::ERREUR_EXPIRE;					// Et enfin affichage d'un message
				} else {
					echo $stoken . '<br/>' . ($A -54321054321) . '<br/>' . $B . '<br/>' . $E . '<br/>' . $P;	
				}
			} else { echo $stoken; }
		} else { echo $resp; }
		
	} else {
		echo 'FAUX !';
	}