	<?php
  function quote_smart($value)
		{
        global $sqlcon;
				if (!is_array($value))
				$value = stripslashes($value);
				if ((!is_numeric($value)) && (!is_array($value)))
				$value = "'" . mysql_escape_string($value) . "'";
				if (is_array($value))
				$value = implode($value);
				return $value;
		}  


    function standardSQLInsert($strTableName, $arrValuePairs){
        global $sqlcon;
        $strSeparator = '';
        $strCols = '';
        $strValues = '';
        foreach ($arrValuePairs as $strCol => $strValue) {
            $strCols = $strCols.$strSeparator.$strCol;
            $strValues = $strValues.$strSeparator.quote_smart($strValue);
            $strSeparator = ',';
        }
        mysqli_query($sqlcon, "INSERT INTO $strTableName ($strCols) VALUES($strValues)");
    }

    function standardSQLUpdate($strTableName, $arrValuePairs, $arrConditionPairs){
        global $sqlcon;
        $strSeparator = '';
        $strSetStatements = '';
        $strUpdateConditions = '';
        foreach ($arrValuePairs as $strCol => $strValue){
            $strSetStatements = $strSetStatements.$strSeparator.$strCol.'='.quote_smart($strValue);
            $strSeparator = ',';
        }
        $strSeparator = '';
        foreach ($arrConditionPairs as $strCol => $strValue){
            $strUpdateConditions = $strUpdateConditions.$strSeparator.$strCol.'='.quote_smart($strValue);
            $strSeparator = ' AND ';
        }
        $strUpdateConditions = '('.$strUpdateConditions.')';
        mysqli_query($sqlcon, "UPDATE $strTableName SET $strSetStatements WHERE $strUpdateConditions");
    }
    ?>