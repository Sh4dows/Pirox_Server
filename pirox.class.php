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
			return self::ERREUR_UNREGISTERED; // Le serial n'existe pas !
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
				return $this->_archa;
			}
		}
		
		public function getSubB()
		{
			if ( empty( $this->_basic)){
				return self::ACTION_KO;
			} else {
				return $this->_basic;
			}
		}

		public function getSubE()
		{
			if ( empty( $this->_elite)){
				return self::ACTION_KO;
			} else {
				return $this->_elite;
			}
		}
		
		public function getSubP()
		{
			if ( empty( $this->_premium)){
				return self::ACTION_KO;
			} else {
				return $this->_premium;
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