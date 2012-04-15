<?php

/**
 * ORM list management class
 * 
 * @package siteadm
 */
abstract class db_object_manager
{

	/*
	 * Object name
	 * @var string
	 */
	static protected $name;

	/*
	 * Use cache for objects
	 * @var bool
	 */
	static public $object_cache = false;
	static public $object_cache_ttl = 60;
	
	/*
	 * Objects list
	 * @var array
	 */
	public $list = array();

	/**
	 * Construit la liste à partir de paramètres de recherche
	 *
	 * @param mixed $params
	 */
	public function __construct($params=null)
	{

	}

	/**
	 * Récupération d'une liste d'objets en base de donnée à partir de paramètres de recherche
	 * 
	 * @todo créer un modèle standard pour les paramètres de recherche
	 * @param mixed $params
	 */
	public function db_retrieve($params=null)
	{

		// A surcharger

	}
	
	public function insert($infos)
	{
		$classname = static::name;
		$object = new $classname();
		if ($object->insert($infos))
		{
			$this->cache_update($object);
			return $this->list[$object->id] = $object;
		}
	}
	
	public function get($params)
	{
		if (is_numeric($params) && isset($this->list[(int)$params]))
		{
			return $this->list[(int)$params];
		}
		elseif (is_numeric($params) && ($object=$this->cache_get($params)))
		{
			return $this->list[$object->id] = $object;
		}
		else
		{
			$classname = static::$name;
			$object = new $classname($params);
			if ($object->id)
			{
				$this->cache_update($object);
				return $this->list[$object->id] = $object;
			}
		}
	}
	
	public function delete($id)
	{
		if (is_numeric($id))
		{
			if (isset($this->list[$id]))
				unset($this->list[$id]);
			$this->cache_delete($id);
		}
	}
	
	/**
	 * Cache an object
	 * 
	 * @param int $id
	 */
	public function cache_get($id)
	{
		if (static::$object_cache && ($object=apc_fetch(static::$name."_".(int)$id)))
		{
			return $object;
		}
	}
	
	/**
	 * Cache an object
	 * 
	 * @param db_object $object
	 */
	public function cache_update(db_object $object)
	{
		if (static::$object_cache && is_a($object, static::$name) && $object->id)
		{
			apc_store(static::$name."_".$object->id, $object, static::$object_cache_ttl);
		}
	}
	
	/**
	 * Cache an object
	 * 
	 * @param mixed $param
	 */
	public function cache_delete($param)
	{
		if (static::$object_cache)
		{
			if (is_numeric($param))
				return apc_delete(static::$name."_".(int)$param);
			elseif (get_class($param) == static::$name && $param->id)
				return apc_delete(static::$name."_".$param->id);
		}
	}
	
}

/**
 * ORM class
 *
 * @package siteadm
 */
abstract class db_object
{

	/*
	 * Object name (get_called_class())
	 * @var string
	 */
	static protected $_name = "";

	/*
	 * Database main table
	 * @var string
	 */
	static protected $_db_table = "";
	
	/*
	 * Last update timestamp
	 * @var int (timestamp)
	 */
	protected $_update;

	/*
	 * Database PRIMARY key
	 * @var int
	 */
	public $id;

	/*
	 * Fields Specifications
	 * @example array(
	 * 	"machin_id" => array("type"=>"object", "otype"=>"account", "dep"=>true),
	 * 	"name" => array("type"=>"string", "size"=>32),
	 * 	"choice" => array("type"=>"select", "list"=>array(0, 1, 2)),
	 * 	"number" => array("type"=>"number", "size"=>8),
	 * 	"ok" => array("type"=>"bool"),
	 * 	"price" => array("type"=>"float"),
	 * );
	 * @var array
	 */
	static protected $_f = array();

	static function _manager()
	{
		$classname = static::$_name;
		return $classname();
	}
	
	/**
	 * Retrieve info about a field
	 *
	 * @param string $name
	 * @param string $param
	 * @return mixed
	 */
	static function field($name=null, $param=null)
	{

		if (is_string($name) && isset(static::$_f[$name]))
		{
			if (is_string($param) && isset(static::$_f[$name][$param]))
				return static::$_f[$name][$param];
			else
				return static::$_f[$name];
		}
		elseif ($name===null)
		{
			return static::$_f;
		}
		
	}

	/**
	 * Object name
	 * 
	 * Recommanded to overload
	 * @return string
	 */
	function __toString()
	{

		if (array_key_exists("label", static::$_f) && !is_null($this->label))
			return "$this->label";
		elseif (array_key_exists("name", static::$_f) && !is_null($this->label))
			return "$this->name";
		else
			return get_called_class($this)." ID#$this->id";

	}

	/**
	 * Object update url
	 * 
	 * Recommanded to overload
	 * @return string
	 */
	function url()
	{

		if ($this->id)
			return get_called_class($this).".php?id=$this->id";

	}

	/**
	 * Object update link
	 *
	 * @return string
	 */
	function link()
	{
		if ($this->id)
			return "<a href=\"".$this->url()."\">".$this->__tostring()."</a>";

	}

	// ACCESS

	/**
	 * Construct
	 * 
	 * Two ways : directly using $infos or using database query $params
	 * @param mixed $params
	 * @param array $infos
	 */
	function __construct($params=null, $infos=array())
	{
		
		if (!is_array($infos))
			$infos = array();
		
		foreach(static::$_f as $name=>$field)
		{
			if (!isset($infos[$name]))
				$this->{$name} = null;
			elseif ($name == "id" && is_numeric($infos[$name]))
				$this->{$name} = (int)$infos[$name];
			else
				$this->set($name, $infos[$name]);
		}

		// From DB
		if ($params !== null)
		{
			$this->db_retrieve($params);
		}

	}

	// PERM
	
	/**
	 * Returns the insert permission level of the connected user for this object 
	 * 
	 * TO BE OVERLOADED
	 * @return string|bool
	 */
	static public function insert_perm()
	{
	
	if (login()->perm("admin"))
		return "admin";
	else
		return false;
	
	}
	
	/**
	 * Returns the update permission level of the connected user for this object 
	 * 
	 * TO BE OVERLOADED
	 * @return string|bool
	 */
	public function update_perm()
	{
	
	if (login()->perm("admin"))
		return "admin";
	else
		return false;
	
	}
	
	/**
	 * Returns the delete permission level of the connected user for this object 
	 * 
	 * TO BE OVERLOADED
	 * @return string|bool
	 */
	public function delete_perm()
	{
	
	if (login()->perm("admin"))
		return "admin";
	else
		return false;
	
	}
	
	// UPDATE

	/**
	 * Standard field setter
	 *
	 * Set a field using specifications array $_f
	 * @param string $name
	 * @param array $infos
	 * @return void
	 */
	public function set($name, $value)
	{
		if (!is_string($name) || !array_key_exists($name, static::$_f) || !array_key_exists("type", static::$_f[$name]))
			return;
		$this->{$name} = $value;
		$this->convert($name, $this->{$name});
	}
	
	/**
	 * Standard field converter
	 *
	 * Convert if needed using specifications array $_f
	 * @param string $name
	 * @param mixed $value
	 * @return bool
	 */
	public function convert($name, &$value)
	{
		
		if (!is_string($name) || !array_key_exists($name, static::$_f) || !array_key_exists("type", static::$_f[$name]))
			return false;

		switch (static::$_f[$name]["type"])
		{
			// Boolean
			case "bool":
			case "boolean":
				if ($value === "1")
					$value = "1";
				elseif ($value !== "0")
					$value = null;
				break;
			// String (param size)
			case "str":
			case "string":
				if (!is_string($value))
					$value = null;
				break;
			// Object (param otype)
			case "obj":
			case "object":
				if (!is_numeric($value) || !($class=static::$_f[$name]["otype"]) || !($class($value)))
					$value = null;
				break;
			// Object list (param otype)
			case "obj_list":
			case "object_list":
				if (!is_array($value) || !($class=static::$_f[$name]["otype"]))
				{
					$value = array();
				}
				else
				{
					foreach($value as $nb=>$v)
						if ($class($v))
							unset($value[$nb]);
				}
				break;
			// Number (param size)
			case "numeric":
			case "number":
				if (!is_numeric($value))
					$value = (float)$value;
				break;
			// Integer (param size)
			case "int":
			case "integer":
				if (!is_int($value))
					$value = (int)$value;
				break;
			// Floating point number (param size)
			case "float":
				if (!is_float($value))
					$value = (float)$value;
				break;
			// Element of a list (param list)
			case "enum":
			case "select":
				if (!(is_string($value) || is_numeric($value)) || !in_array($value, static::$_f[$name]["list"]))
					if (isset(static::$_f[$name]["default"]))
						$value = static::$_f[$name]["default"];
					else
						$value = null;
			// Default (no control)
			default :
				break;
		}
		
		return true;
	
	}

	/**
	 * Update object with dependances, cache, script and database triggers
	 *
	 * @param array $infos
	 * @param bool $db_update
	 * @return bool
	 */
	public function insert($infos)
	{

		if (!static::insert_perm())
			return false;
		if (!is_array($infos))
			return false;

		//var_dump($infos);

		foreach ($infos as $name=>&$value)
		{
			// Field control
			if (!$this->convert($name, $value))
				unset($infos[$name]);
		}
		
		if (count($infos) && ($this->db_insert($infos)))
		{
			foreach($infos as $name=>$value)
				$this->{$name} = $value;
			$this->_manager()->cache_update($this);
			$this->dep_insert($infos);
			$this->root_insert();
			return $this;
		}
		else
		{
			return false;
		}

	}
	
	/**
	 * On force la mise à jour des objets liés
	 */
	protected function dep_insert()
	{
		foreach(static::$_f as $name=>$field)
		{
			if (isset($field["type"]) && $field["type"] == "object" && !empty($field["dep"]) && $this->{$name})
			{
				$classname = $field["otype"];
				// TODO : Attention ! l'object peut être lié à un autre endroit
				// Préférer un db_retrieve ?
				// Gérer à la main au cas par cas ? (pas trop)
				if (isset($classname()->list[$this->{$name}]))
					unset($classname()->list[$this->{$name}]);
				$classname()->cache_delete($this->{$name});
			}
		}
	}
	
	/**
	 * Update object with dependances, preupdate, script, cache and database triggers
	 *
	 * @param array $infos
	 * @param bool $db_update
	 * @return bool
	 */
	public function update($infos)
	{

		if (!$this->update_perm())
			return false;
		if (!is_array($infos) || !$this->id)
			return false;

		foreach ($infos as $name=>&$value)
		{
			// Contrôle des champs
			if (!$this->convert($name, $value))
				unset($infos[$name]);
		}

		if (count($infos) && $this->db_update($infos))
		{
			$this->root_preupdate($infos);
			foreach($infos as $name=>$value)
				$this->{$name} = $value;
			$this->_manager()->cache_update($this);
			$this->dep_update($infos);
			$this->root_update();
		}

	}

	/**
	 * On force la mise à jour des objets liés
	 */
	public function dep_update($infos)
	{
		foreach(static::$_f as $name=>$field)
		{
			if (isset($field["type"]) && $field["type"] == "object" && !empty($field["dep"]))
			{
				// TODO : Même remarque que pour dep_insert();
				if (isset($infos[$name]) && $infos[$name] != $this->{$name})
				{
					$classname = $field["otype"];
					if ($this->{$name})
					{
						$classname()->cache_delete($this->{$name});
						if (isset($classname()->list[$this->{$name}]))
							unset($classname()->list[$this->{$name}]);
					}
					if ($infos[$name])
					{
						$classname()->cache_delete($this->{$infos[$name]});
						if (isset($classname()->list[$this->{$infos[$name]}]))
							unset($classname()->list[$this->{$infos[$name]}]);
					}
				}
			}
		}
	}
	
	/**
	 * Delete object
	 * 
	 * @return bool
	 */
	public function delete()
	{

		if (!$this->delete_perm())
			return false;
		if (!$this->id)
			return false;

		$this->root_delete();
		$this->db_delete();
		$this->_manager()->delete($this->id);
		$this->dep_update();
		return true;

	}

	public function dep_delete()
	{
		foreach(static::$_f as $name=>$field)
		{
			if (isset($field["type"]) && $field["type"] == "object" && !empty($field["dep"]) && $this->{$name})
			{
				// TODO : Même remarque que pour dep_insert();
				$classname = $field["otype"];
				$classname()->cache_delete($this->{$name});
				if (isset($classname()->list[$this->{$name}]))
					unset($classname()->list[$this->{$name}]);
			}
		}
	}
	
	// DB

	/**
	 * Retrieve object infos from database
	 *
	 * @param mixed $params
	 * @return bool
	 */
	protected function db_retrieve($params)
	{

		// ID KEY (default)
		if (is_numeric($params))
		{
			$fieldname = "id";
			$key = $params;
		}
		// Other KEY
		elseif (!is_array($params) || count($params) != 1 || !(list($fieldname, $key)=each($params)) || !isset(static::$_f[$fieldname]) || empty(static::$_f[$fieldname]["key"]))
		{
			return;
		}

		// Field list to retrieve
		$query_list = array("id");
		foreach(static::$_f as $name=>$field)
		{
			// Filter main fields
			if (isset($field["type"]) && !isset($field["db_table"]))
			{
				if (isset($field["db_fieldname"]))
					$query_list[] = "`".$field["db_fieldname"]."` as $name";
				else
					$query_list[] = "`$name`";
			}
		}

		$query_string = "SELECT ".implode(", ", $query_list)." FROM `".static::$_db_table."` WHERE `$fieldname`='$key'";
		$query = mysql_query($query_string);
		//echo mysql_error();
		if (mysql_num_rows($query) == 1)
		{
			foreach(mysql_fetch_assoc($query) as $name=>$value)
				$this->{$name} = $value;
			foreach(static::$_f as $name=>$field)
			{
				/*
				 * TODO : A FINIR !
				if (isset($field["type"]) && $field["type"] == "object_list")
				{
					$this->{$name} = array(); 
					if (!($fieldname=$field["fieldname"]))
						$fieldname = $name."_id";
					if (!($paramname=$field["paramname"]))
						$paramname = static::get_called_class($this)."_id";
					$query_string = "SELECT `".$fieldname."` FROM `".$field["tablename"]."` WHERE `".$paramname."`='$this->id'";
					$query = mysql_query($query_string);
					while(list($id)=mysql_fetch_row($query))
						$this->{$name}[] = $id;
				}
				*/
			}
			$this->db_retrieve_more($this->id);
			return true;
		}

	}
	
	/**
	 * Retrieve more infos from DB
	 * 
	 * OVERLOAD
	 * @param int $id
	 */
	public function db_retrieve_more($id)
	{
		// OVERLOAD IF NEEDED
	}
	
	/**
	 * Insert object into database
	 *
	 * @param array $infos
	 * @return bool
	 */
	protected function db_insert($infos)
	{

		if ($this->id || !is_array($infos))
			return false;

		// Verif required fields
		foreach (static::$_f as $name=>$field)
			if (!empty($field["nonempty"]) && !isset($infos[$name]))
				return false;

		// Construct query
		$query_fields = array();
		$query_values = array();
		foreach($infos as $name=>$value)
		{
			// On sélectionne les champs de type standard (défini en interne)
			if (isset(static::$_f[$name]) && isset(static::$_f[$name]["type"]))
			{
				if (isset(static::$_f[$name]["db_fieldname"]))
					$query_fields[] = "`".static::$_f[$name]["db_fieldname"]."`";
				else
					$query_fields[] = "`$name`";
				if ($value === null)
					$query_values[] = "NULL";
				else
					$query_values[] = "'".mysql_real_escape_string((string)$value)."'";
				unset($infos[$name]);
			}
		}
		if (count($query_fields))
		{
			echo $query_string = "INSERT INTO `".static::$_db_table."` (".implode(", ", $query_fields).") VALUES (".implode(", ", $query_values).")";
			$query = mysql_query($query_string);
			if ($this->id = mysql_insert_id())
			{
				$this->db_insert_more($infos);
				return true;
			}
		}

		return false;

	}
	protected function db_insert_more($infos)
	{
		// OVERLOAD IF NEEDED
	}

	/**
	 * Update object into database
	 *
	 * @param array $infos
	 * @return bool
	 */
	protected function db_update($infos)
	{

		if (!$this->id || !is_array($infos))
			return false;

		$query_update = array();

		//echo "<div>"; var_dump($infos); echo "</div>\n";

		foreach ($infos as $name=>$value)
		{
			// On sélectionne les champs de type standard (défini en interne)
			if (isset(static::$_f[$name]) && isset(static::$_f[$name]["type"]))
			{
				if (isset(static::$_f[$name]["db_fieldname"]))
					$fieldname = static::$_f[$name]["db_fieldname"];
				else
					$fieldname = $name;
				if ($value === null)
					$query_update[$name] = "`$fieldname`=NULL";
				else
					$query_update[$name] = "`$fieldname`='".mysql_real_escape_string((string)$value)."'";
				unset($infos[$name]);
			}
		}
		if (count($query_update))
		{
			$query_string = "UPDATE `".static::$_db_table."` SET ".implode(", ", $query_update)." WHERE `id`='$this->id'";
			$query = mysql_query($query_string);
			//echo "<p>$query_string : ".mysql_affected_rows()."</p>\n";
			if (mysql_affected_rows() || $this->db_update_more($infos))
			{
				return true;
			}
		}

		return false;

	}
	protected function db_update_more($infos)
	{
		// OVERLOAD IF NEEDED
	}

	/**
	 * Delete recording in database
	 * 
	 * @return bool
	 */
	protected function db_delete()
	{

		if (!$this->id)
			return;

		$query_string = "DELETE FROM `".static::$_db_table."` WHERE `id`='$this->id'";
		mysql_query($query_string);
		if (mysql_affected_rows() || $this->db_delete_more())
			return true;
		else
			return false;

	}
	protected function db_delete_more($infos)
	{
		// OVERLOAD IF NEEDED
	}

	// ROOT ACCESS SCRIPTS

	/**
	 * Script to execute as root
	 * post-insert trigger
	 */
	protected function root_insert()
	{

		// To be extended
		if (!$this->id)
			return;
		exec("sudo ".SITEADM_EXEC_DIR."/db_object.psh ".get_called_class()." $this->id insert");

	}

	/**
	 * Script to execute as root
	 * post-update trigger
	 */
	protected function root_preupdate($infos)
	{

		// To be extended

	}
	
	/**
	 * Script to execute as root
	 * post-update trigger
	 */
	protected function root_update()
	{

		// To be extended
		if ($this->id)
			exec("sudo ".SITEADM_EXEC_DIR."/db_object.psh ".get_called_class()." $this->id update");

	}
	
	/**
	 * Script to execute as root
	 * post-deletion trigger
	 */
	protected function root_delete()
	{

		if ($this->id)
			exec("sudo ".SITEADM_EXEC_DIR."/db_object.psh ".get_called_class()." $this->id delete");

	}
	
	/**
	 * Post-insert trigger
	 */
	protected function script_insert()
	{
	
		// TO BE OVERLOADED
	
	}
		
	/**
	 * Post-update trigger
	 */
	protected function script_update()
	{
	
		// TO BE OVERLOADED
	
	}
		
	/**
	 * Post-delete trigger
	 */
	protected function script_delete()
	{
	
		// TO BE OVERLOADED
	
	}
	
}

?>