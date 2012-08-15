<?php
	class Pirox
	{
		private $_authdb;
		private $_id;
		private $_key;
		private $_skey;
		private $_basic;
		private $_elite;
		private $_premium;
		private $_archa;
		private $_validity;

		const ACTION_OK				= '[___TRUE]';
		const ACTION_KO				= '[__FALSE]';
		const ERREUR_EXPIRE 		= '[EXPIRED]'; // Expiration de l'abonnement, depuis moins de 1 jour.
		const ERREUR_UNREGISTERED 	= '[UNREGIS]'; // Le serial n'existe pas.
		const ERREUR_UNSUBSCRIBED	= '[UNSUBSC]'; // Expiration de l'abonnement, depuis plus de 1 jour.

		private function calcStamps( $dbStamps, $nwStamps)
		{
			$array = explode( '!', $dbStamps); 	// on split $subTime afin de déterminer la date d'enregistrement et le temps restant
			$recStamps = $array[0];				// date d'abonnement
			$keyPeriod = $array[1]; 			// temps restant de l'abonnement
			if ( $recStamps <= 0){
				return '0!0'; 					// pas d'abonnement..
			}
			$difStamps = $nwStamps -$recStamps;
			$keyPeriod -= 54321054321;			// '54321054321' valeur à soustaire ou à ajouter, by Pirox :p
			if ( $keyPeriod <= 0 ){ 
				return $recStamps.'!0';			// expiration de l'abonnement..
			}
			$keyPeriod -= $difStamps;			// on enlève la différence de temps écoulé
			$keyPeriod += 54321054321;
			return $recStamps.'!'.$keyPeriod;	// on retourne la nouvelle valeur
		}
		
		private function makeStamps()
		{
			$heur = date( 'G') +2;				// heure, +2 sinon l'heure est fausse
			$minu = date( 'i');					// les minutes
			$seco = date( 's');					// les secondes
			$jour = date( 'j');					// le jour du mois
			$mois = date( 'n');					// le mois 1 à 12
			$anne = date( 'Y');					// l'année au format AAAA
			return mktime( $heur, $minu, $seco, $mois, $jour , $anne); // Enfin on récupère la date en (s)econde
		}

		public function __construct($dbName, $dbUser, $dbPass)
		{
			/* Identification base de donnée */
			$this->_authdb = new PDO( 'mysql:host=localhost;dbname='.$dbName, $dbUser, $dbPass);
		}

		public function dbGetData( $key)
		{
			$requestdb = $this->_authdb->query('SELECT * FROM pirox_keys');
			while ($datadb = $requestdb->fetch()){
				if ( $datadb['key'] == $key){
					$this->_id 			= $datadb['id'];
					$this->_skey		= $datadb['key'];
					$this->_skey		= $datadb['skey'];
					$this->_validity 	= $datadb['validity'];
					if ($this->_validity != 0){
						$requestdb2 = $this->_authdb->query( 'SELECT * FROM pirox_subscriptions WHERE id='.$datadb['id']);
						$datadb2 = $requestdb2->fetch();
						$this->_basic 	= $datadb2['basic'];
						$this->_elite 	= $datadb2['elite'];
						$this->_premium = $datadb2['premium'];
						$this->_archa 	= $datadb2['archa'];
					} else {
						return self::ERREUR_UNSUBSCRIBED; // L'abonnement est terminé..
					}
				}
			}
			if ( empty( $this->_id) ){ return self::ERREUR_UNREGISTERED; } // Le serial n'existe pas !
		}
		
		public function dbUpdateData()
		{
			$requestdb  = $this->_authdb->prepare('UPDATE pirox_keys SET validity = :v WHERE id = :id');
			$requestdb->execute(array(
				'v' => $this->_validity,
				'id' => $this->_id
			));
			$requestdb = $this->_authdb->prepare('UPDATE pirox_subscriptions SET basic = :b, elite = :e, premium = :p, archa = :a WHERE id = :id');
			$requestdb->execute(array(
				'b'  => $this->_basic,
				'e'  => $this->_elite,
				'p'  => $this->_premium,
				'a'  => $this->_archa,
				'id' => $this->_id
			));
		}
		
		public function getId()
		{
			if ( empty( $this->_id)){
				return self::ACTION_KO;
			} else {
				return $this->_id;
			}
		}

		public function getKey()
		{
			if ( empty( $this->_key)){
				return self::ACTION_KO;
			} else {
				return $this->_key;
			}
		}
		
		public function getKeyServer()
		{
			if ( empty( $this->_skey)){
				return self::ACTION_KO;
			} else {
				return $this->_skey;
			}
		}
		
		public function getSubA()
		{
			if ( empty( $this->_archa)){
				return self::ACTION_KO;
			} else {
				$nowStamps = $this->makeStamps();
				$sub = $this->calcStamps( $this->_archa, $nowStamps);
				$tmp = $this->updateSubA( $sub);
				if ( $tmp == '[___TRUE]'){
					$sub = explode( '!', $sub);
					return $sub[1];
				} else { return self::ACTION_KO; }
			}
		}
		
		public function getSubB()
		{
			if ( empty( $this->_basic)){
				return self::ACTION_KO;
			} else {
				$nowStamps = $this->makeStamps();
				$sub = $this->calcStamps( $this->_basic, $nowStamps);
				$tmp = $this->updateSubB( $sub);
				if ( $tmp == '[___TRUE]'){
					$sub = explode( '!', $sub);
					return $sub[1];
				} else { return self::ACTION_KO; }
			}
		}

		public function getSubE()
		{
			if ( empty( $this->_elite)){
				return self::ACTION_KO;
			} else {
				$nowStamps = $this->makeStamps();
				$sub = $this->calcStamps( $this->_elite, $nowStamps);
				$tmp = $this->updateSubE( $sub);
				if ( $tmp == '[___TRUE]'){
					$sub = explode( '!', $sub);
					return $sub[1];
				} else { return self::ACTION_KO; }
			}
		}
		
		public function getSubP()
		{
			if ( empty( $this->_premium)){
				return self::ACTION_KO;
			} else {
				$nowStamps = $this->makeStamps();
				$sub = $this->calcStamps( $this->_premium, $nowStamps);
				$tmp = $this->updateSubP( $sub);
				if ( $tmp == '[___TRUE]'){
					$sub = explode( '!', $sub);
					return $sub[1];
				} else { return self::ACTION_KO; }
			}
		}
		
		public function getValidity()
		{
			if ( empty( $this->_validity)){
				return self::ACTION_KO;
			} else {
				return $this->_validity;
			}
		}
		
		public function updateSubA( $value)
		{
			if ( !empty( $value) ){
				$this->_archa = $value;
				return self::ACTION_OK;
			} else {
				return self::ACTION_KO;
			}
		}

		public function updateSubB( $value)
		{
			if ( !empty( $value) ){
				$this->_basic = $value;
				return self::ACTION_OK;
			} else {
				return self::ACTION_KO;
			}
		}
	
		public function updateSubE( $value)
		{
			if ( !empty( $value) ){
				$this->_elite = $value;
				return self::ACTION_OK;
			} else {
				return self::ACTION_KO;
			}
		}
		
		public function updateSubP( $value)
		{
			if ( !empty( $value) ){
				$this->_premium = $value;
				return self::ACTION_OK;
			} else {
				return self::ACTION_KO;
			}
		}
		
		public function updateValidity( $value)
		{
			if ( !empty( $value) ){
				$this->_validity = $value;
				return self::ACTION_OK;
			} else {
				return self::ACTION_KO;
			}
		}

	}
?>