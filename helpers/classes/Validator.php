<?php

class Validator
{
	public
	$_errors = [],
	$_db     = null,
	$_rules_broken = [],
	$_alias = [
		'unx'=>'username',
		'emx'=>'email',
	];


	public function __construct($dbo)  {
		$this->_db = $dbo;
	}

	public function check($source, $items=[], $sanitize=false) {

		$this->_errors = [];

		foreach ($items as $item => $rules) {
			if($sanitize){
				$item = sanitize($item);
			}

			$display = $rules['display'];
			if(!isset($source[$item])){
				$source[$item] = "";
			}

			foreach ($rules as $rule => $rule_value) {
				$value = $source[$item];

				if ($sanitize) {
					$value = sanitize(trim($value));
				}

				$length = is_array($value) ? count($value) : strlen($value);
				$verb   = is_array($value) ? "are"         : "is";

				if ($rule==='required'  &&  $length==0) {
					$str = 'Kræves';
					if ($rule_value){
						$this->addError(["{$display} $str",$item]);
						$this->ruleBroken([$item,"required",true]);
					}
				}
				else
				if ($length != 0) {
					switch ($rule) {
						case 'min':
						if (is_array($rule_value))
						$rule_value = max($rule_value);
						$str = 'min';
						$str1 = 'karakter';
						$str2 = 'Kræves';
						if ($length < $rule_value){
							$this->addError(["{$display} $str {$rule_value} $str1 $str2",$item]);
							$this->ruleBroken([$item,"min",$rule_value]);
						}
						break;

						case 'max':
						if (is_array($rule_value))
						$rule_value = min($rule_value);
						$str = 'min';
						$str1 = 'karakter';
						$str2 = 'Kræves';
						if ($length > $rule_value){
							$this->addError(["{$display} $str {$rule_value} $str1 $str2",$item]);
							$this->ruleBroken([$item,"max",$rule_value]);
						}
						break;

						case 'matches':
						if (!is_array($rule_value))
						$array = [$rule_value];
						$str = 'Og';
						$str1 = 'Skal være det samme';
						foreach ($array as $rule_value)
						if ($value != trim($source[$rule_value])){
							$this->addError(["{$items[$rule_value]['display']} $str {$display} $str1",$item]);
							$this->ruleBroken([$item,"matches",$source]);
						}
						break;

						case 'unique':
							$table  = is_array($rule_value) ? $rule_value[0] : $rule_value;		
							if($table == "users" && array_key_exists($item, $this->_alias)) {
								$orig = $item;
								$item = $this->_alias[$item];
							}

							$field = $item; // The field name to be checked
							if ($table == "users" && ($field == "username" || $field == "email")) {
								// Special logic for users table when checking username or email
								$query = "SELECT id FROM users WHERE (email = ?) OR (username = ?)";
								$count = sizeof($this->_db->query($query, [$value, $value])->fetchAll());
				
							} else {
								// Standard logic for other tables/fields
								$query = "SELECT id FROM {$table} WHERE {$field} = ?";
								$count = sizeof($this->_db->query($query, [$value])->fetchAll());
							}
							if(isset($orig)){
								$item = $orig;
							}
							$str = 'Findes allerede. Vælg venligst en anden';
							if ($count > 0) {
								$this->addError(["{$display} $str {$display}", $item]);
								$this->ruleBroken([$item, "unique", false]);
							}

							break;
						
						case 'unique_update':
							$t     = explode(',', $rule_value);
							$table = $t[0];
							$id    = $t[1];
							if($table == "users" && array_key_exists($item, $this->_alias)) {
								$orig = $item;
								$item = $this->_alias[$item];
							}

							if ($table == "users" && ($item == "username" || $item == "email")) {
						
								$query = "SELECT id FROM users WHERE id != ? AND ((email = ?) OR (username = ?))";
								$count = sizeof($this->_db->query($query, [$id, $value, $value])->fetchAll());
							} else {
								$query = "SELECT id FROM {$table} WHERE id != ? AND {$item} = ?";
								$count = sizeof($this->_db->query($query, [$id, $value])->fetchAll());
							}
							if(isset($orig)){
								$item = $orig;
							}
							$str = 'Findes allerede. Vælg venligst en anden';
							if ($count > 0) {
								$this->addError(["{$display} $str {$display}", $item]);
								$this->ruleBroken([$item, "unique_update", false]);
							}
	
							break;

						case 'is_numeric': case 'is_num':
						$str = 'Skal være et tal';
						if ($rule_value  &&  !is_numeric($value)){
							$this->addError(["{$display} $str",$item]);
							$this->ruleBroken([$item,"is_numeric",false]);
						}

						break;

						case 'valid_email':
						$str = 'Skal være en gyldig e-mailadresse';
						if(!filter_var($value,FILTER_VALIDATE_EMAIL)){
							$this->addError(["{$display} $str",$item]);
							$this->ruleBroken([$item,"valid_email",false]);
						}

						break;

						case 'is_not_email':
						if($rule_value){
							$str = 'Kan ikke være en e-mailadresse';
							if(filter_var($value,FILTER_VALIDATE_EMAIL)){
								$this->addError(["{$display} $str",$item]);
								$this->ruleBroken([$item,"is_not_email",false]);
							}
						}
						break;

						case 'valid_email_beta':
						$str = 'Skal være en gyldig e-mailadresse';
						if(!filter_var($value,FILTER_VALIDATE_EMAIL)){
							$this->addError(["{$display} $str",$item]);
							$this->ruleBroken([$item,"valid_email_beta",false]);
						}

						$email_parts = explode('@', $value);
						$str = 'Skal tilhøre en gyldig server';
						if ((!filter_var(gethostbyname($email_parts[1]), FILTER_VALIDATE_IP) && !filter_var(gethostbyname('www.' . $email_parts[1]), FILTER_VALIDATE_IP)) && !getmxrr($email_parts[1], $mxhosts)){
							$this->addError(["{$display} $str",$item]);
							$this->ruleBroken([$item,"valid_email_server",false]);
						}
						break;

						case '<'  :
						case '>'  :
						case '<=' :
						case '>=' :
						case '!=' :
						case '==' :
						$array = is_array($rule_value) ? $rule_value : [$rule_value];

						foreach ($array as $rule_value)
						if (is_numeric($value)) {
							$rule_value_display = $rule_value;

							if (!is_numeric($rule_value)  &&  isset($source[$rule_value])) {
								$rule_value_display = $items[$rule_value]["display"];
								$rule_value         = $source[$rule_value];
							}

							if ($rule=="<"  &&  $value>=$rule_value){
								$str = 'Skal være mindre end';
								$this->addError(["{$display} $str {$rule_value_display}",$item]);
								$this->ruleBroken([$item,"<",$rule_value]);
							}

							if ($rule==">"  &&  $value<=$rule_value){
								$str = 'Skal være større end';
								$this->addError(["{$display} $str {$rule_value_display}",$item]);
								$this->ruleBroken([$item,">",$rule_value]);
							}

							if ($rule=="<="  &&  $value>$rule_value){
								$str = 'Skal være mindre end eller lig med';
								$this->addError(["{$display} $str {$rule_value_display}",$item]);
								$this->ruleBroken([$item,"<=",$rule_value]);
							}

							if ($rule==">="  &&  $value<$rule_value){
								$str = 'Skal være større end eller lig med';
								$this->addError(["{$display} $str {$rule_value_display}",$item]);
								$this->ruleBroken([$item,">=",$rule_value]);
							}

							if ($rule=="!="  &&  $value==$rule_value){
								$str = 'Må ikke være lig med';
								$this->addError(["{$display} $str {$rule_value_display}",$item]);
								$this->ruleBroken([$item,"!=",$rule_value]);
							}

							if ($rule=="=="  &&  $value!=$rule_value){
								$str = 'Skal være lig med';
								$this->addError(["{$display} $str {$rule_value_display}",$item]);
								$this->ruleBroken([$item,"==",$rule_value]);
							}
						}
						else{
							$str = 'Skal være et tal';
							$this->addError(["{$display} $str",$item]);
							$this->ruleBroken([$item,"val_num",false]);
						}
						break;

						case 'is_integer': case 'is_int':
						if ($rule_value  &&  filter_var($value, FILTER_VALIDATE_INT)===false){
							$str = 'Skal være et helt tal';
							$this->addError(["{$display} $str",$item]);
							$this->ruleBroken([$item,"is_int",false]);
						}
						break;

						case 'is_timezone':
						if ($rule_value)
						if (array_search($value, DateTimeZone::listIdentifiers(DateTimeZone::ALL)) === FALSE){
							$str = 'Skal være et gyldigt tidszonenavn';
							$this->addError(["{$display} $str",$item]);
							$this->ruleBroken([$item,"is_timezone",false]);
						}
						break;

						case 'in':
						$verb           = 'Skal være';
						$list_of_names  = [];	// if doesn't match then display these in an error message
						$list_of_values = [];	// to compare it against

						if (!is_array($rule_value))
						$rule_value = [$rule_value];

						foreach($rule_value as $val)
						if (!is_array($val)) {
							$list_of_names[]  = $val;
							$list_of_values[] = strtolower($val);
						} else
						if (count($val) > 0) {
							$list_of_names[]  = $val[0];
							$list_of_values[] = strtolower((count($val)>1 ? $val[1] : $val[0]));
						}

						if (!is_array($value)) {
							$verb  = 'Skal være en af følgende';
							$value = [$value];
						}

						foreach ($value as $val) {
							if (array_search(strtolower($val), $list_of_values) === FALSE) {
								$this->addError(["{$display} {$verb}: ".implode(', ',$list_of_names),$item]);
								$this->ruleBroken([$item,"is_in_list",false]);
								break;
							}
						}
						break;

						case 'is_datetime':
						if ($rule_value !== false) {
							$object = DateTime::createFromFormat((empty($rule_value) || is_bool($rule_value) ? "Y-m-d H:i:s" : $rule_value), $value);

							if (!$object  ||  DateTime::getLastErrors()["warning_count"]>0  ||  DateTime::getLastErrors()["error_count"]>0){
								$str = 'Skal være et gyldigt tidspunkt';
								$this->addError(["{$display} $str",$item]);
								$this->ruleBroken([$item,"is_datetime",false]);

							}
						}
						break;

						case 'is_in_array':
						if(!is_array($rule_value)){ //If we're not checking $value against an array, that's a developer fail.
							$str = 'Fatal fejl  Kontakt venligst system administratoren';
							$this->addError(["{$display} $str",$item]);
						} else {
							$to_be_checked = $value; //The value to checked
							$array_to_check_in = $rule_value; //The array to check $value against
							if(!in_array($to_be_checked, $array_to_check_in)){
								$str = 'Er ikke et gyldigt valg';
								$this->addError(["{$display} $str",$item]);
								$this->ruleBroken([$item,"is_in_array",$array_to_check_in]);
							}
						}
						break;

						case 'is_in_database':
						$table  = is_array($rule_value) ? $rule_value[0] : $rule_value;
						$fields = is_array($rule_value) ? $rule_value[1] : [$item, '=', $value];

						if ($this->_db->get($table, $fields)) {
							$str = 'Findes allerede. Vælg venligst en anden';
							$str1 = 'Databasefejl';
							if (sizeof($this->_db->fetchAll())==0) {
								$this->addError(["{$display} $str {$display}",$item]);
								$this->ruleBroken([$item,"is_in_database",false]);

							} else {
								$this->addError([$str1,$item]);
								$this->ruleBroken([$item,"is_in_database",false]);
							}
						}
						break;

						case 'is_valid_north_american_phone':
						$numeric_only_phone = preg_replace("/[^0-9]/", "", $value); //Strip out all non-numeric characters
						$str = 'Skal være et gyldigt nordamerikansk telefonnummer';
						if($numeric_only_phone[0] == 0 || $numeric_only_phone[0] == 1){ //It the number starts with a 0 or 1, it's not a valid North American phone number.
							$this->addError(["{$display} $str",$item]);
							$this->ruleBroken([$item,"is_valid_north_american_phone",false]);
						}
						if(strlen($numeric_only_phone) != 10){ //Valid North American phone numbers are 10 digits long
							$this->addError(["{$display} $str",$item]);
							$this->ruleBroken([$item,"is_valid_north_american_phone",false]);
						}
						break;
					}
				}
			}

		}

		return $this;
	}

	public function addError($error) {
		if (array_search($error, $this->_errors) === FALSE){
			if(is_array($error) && count($error) > 1){
				$this->_errors[] = $error[0];
			}else{
				$this->_errors[] = $error;
			}

		}
	}

	public function ruleBroken($rule){
		if (array_search($rule, $this->_rules_broken) === FALSE){
			$this->_rules_broken[] = $rule;
		}
	}

	public function display_errors() {
		$html = "<UL CLASS='bg-danger'>";

		foreach($this->_errors as $error) {
			if (is_array($error))
			$html    .= "<LI CLASS=''>{$error[0]}</LI>
			<SCRIPT>jQuery('document').ready(function(){jQuery('#{$error[1]}').parent().closest('div').addClass('has-error');});</SCRIPT>";
			else
			$html .= "<LI CLASS=''>{$error}</LI>";
		}

		$html .= "</UL>";
		return $html;
	}

	public function rulesBroken(){
		return $this->_rules_broken;
	}

	public function errors(){
		return $this->_errors;
	}

	public function passed(){
		return empty($this->_errors);
	}
}
