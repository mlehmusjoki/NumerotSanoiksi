<?php
	class Numbers {
		private $numbers = array(
			0 => '',
			1 => 'yksi',
			2 => 'kaksi',
			3 => 'kolme',
			4 => 'neljä',
			5 => 'viisi',
			6 => 'kuusi',
			7 => 'seitsemän',
			8 => 'kahdeksan',
			9 => 'yhdeksän'
		);
		private $ordersOfMag = array(
			1 => 'kymmenen',
			2 => 'sata',
			3 => 'tuhat',
			6 => 'miljoona',
			9 => 'miljardi'
		);
		private $partitiveOrdersOfMag = array(
			1 => 'kymmentä',
			2 => 'sataa',
			3 => 'tuhatta',
			6 => 'miljoonaa',
			9 => 'miljardia'
		);

		/**
		 * @param $number int
		 * @return int
		 */

		public function FindOrderOfMagnitude($number) {
			/*
			 * Alternatively could have just checked String length
			 */
			$mag=0;
			while ($number>10**$mag) {
				$mag++;
			}
			if ($number!=10**$mag) {
				$mag--;
			}
			return $mag;
		}

		/**
		 * @param $number string
		 */

		public function ToWords($number) {
			$mag = $this->FindOrderOfMagnitude(intval($number));
			$incrementsLeft = $mag;
			$thereWasASpecialKid = false; //---toista
			$thereHasBeenANumber = false;
			$str = "";

			while($incrementsLeft>=0) {
				$thisNumber = substr($number, $mag-$incrementsLeft, 1);

					//sataa

				if($incrementsLeft == 2 || $incrementsLeft == 5 || $incrementsLeft == 8) {
					if ($thisNumber != '0') {
						$thereHasBeenANumber = true;
						if ($thisNumber != '1') {
							$str .= $this->numbers[$thisNumber] . $this->partitiveOrdersOfMag[2];
						} else {
							$str .= $this->ordersOfMag[2];
						}
					}
				}

				//kymmentä / toista

				elseif ($incrementsLeft == 1 || $incrementsLeft == 4 || $incrementsLeft == 7) {
					if ($thisNumber != '0') {
						$thereHasBeenANumber = true;
						if ($thisNumber != '1') {
							$str .= $this->numbers[$thisNumber].$this->partitiveOrdersOfMag[1];
						} else {
							$specialKid = substr($number, $mag-$incrementsLeft+1, 1);
							if ($specialKid != 0) {
								$thereWasASpecialKid = true;
								$str .= $this->numbers[$specialKid].'toista';
							} else {
								$str .= $this->ordersOfMag[1];
							}
						}
					}
				}

				elseif ($incrementsLeft == 0 || $incrementsLeft == 3 || $incrementsLeft == 6) {
					if ($thisNumber != 0) {
						$thereHasBeenANumber = true;
					}
					if (($incrementsLeft == 0 || $thisNumber != '1') && !$thereWasASpecialKid) {
						$str .= $this->numbers[$thisNumber];
					}
					if ($incrementsLeft != 0) {
						if (isset($this->partitiveOrdersOfMag[$incrementsLeft]) && $thereHasBeenANumber) {
							//this inserts the magnitude
							if ($thisNumber != 1) {
								$str .= $this->partitiveOrdersOfMag[$incrementsLeft];
							} else {
								$str .= $this->ordersOfMag[$incrementsLeft];
							}
							$str .= " "; //space for easier debug
							$thereHasBeenANumber = false; //to check if it's not all zero
						}
					}
					if ($thereWasASpecialKid) {
						$thereWasASpecialKid = false;
					}
				}
				$incrementsLeft--;
			}
			echo $str;

		}

		public function ConvertToNumber($str) {
			$specialKid = false;
			if (strpos($str, "toista") != false) {
				$specialKid = true;
				$str = str_replace("toista", "", $str);
			}
			foreach($this->numbers as $number => $name) {
				if ($name == $str) {
					if ($specialKid == true) {
						return 10+intVal($number);
					}
					return $number;
				}
			}
			return $str;
		}

		public function Multiply($arr) {
			$newArray = array();
			$backwards = array_reverse($arr);

			$used = false;
			foreach($backwards as $key => $val) {
				switch($val) {
					case "kymmentä":
						$multiplyWith = $backwards[$key + 1];
						if (intval($multiplyWith) != 0) {
							$newArray[] = intval($multiplyWith) * 10;
							$used = true;
						} else {
							$newArray[] = $val;
						}
						break;
					case "sataa":
						$multiplyWith = $backwards[$key + 1];
						if (intval($multiplyWith) != 0) {
							$newArray[] = intval($multiplyWith) * 100;
							$used = true;
						} else {
							$newArray[] = $val;
						}
						break;
					case "tuhatta":
						$multiplyWith = $backwards[$key + 1];
						if (intval($multiplyWith) != 0) {
							$newArray[] = intval($multiplyWith) * 1000;
							$used = true;
						} else {
							$newArray[] = $val;
						}
						break;
					case "miljoonaa":
						$multiplyWith = $backwards[$key + 1];
						if (intval($multiplyWith) != 0) {
							$newArray[] = intval($multiplyWith) * 1000000;
							$used = true;
						} else {
							$newArray[] = $val;
						}
						break;

					//no multipliers:
					case "kymmenen":
						$newArray[] = 10;
						break;
					case "sata":
						$newArray[] = 100;
						break;
					case "tuhat":
						$newArray[] = 1000;
						break;
					case "miljoona":
						$newArray[] = 1000000;
						break;
					default:
						if ($used == true) {
							$used = false;
							continue;
						}
						$newArray[] = intval($val);
				}
			}
			return array_reverse($newArray);
		}

		public static function Finalize($arr) {
			//Not to be inverted this time
			$newArray = array();
			$backwards = array_reverse($arr);
			$used = false;
			foreach ($backwards as $key => $val) {
				switch($val) {
					case "kymmentä":
						$multiplyWith = $backwards[$key + 1];
						$newArray[] = intval($multiplyWith) * 10;
						$used = true;
						break;
					case "sataa":
						$multiplyWith = $backwards[$key + 1];
						$newArray[] = intval($multiplyWith) * 100;
						$used = true;
						break;
					case "tuhatta":
						$multiplyWith = $backwards[$key + 1];
						$newArray[] = intval($multiplyWith) * 1000;
						$used = true;
						break;
					case "miljoonaa":
						$multiplyWith = $backwards[$key + 1];
						$newArray[] = intval($multiplyWith) * 1000000;
						$used = true;
						break;
					default:
						if ($used == true) {
							$used = false;
							continue;
						}
						$newArray[] = $val;
				}
			}
			return array_sum($newArray);
		}

		public function ToNumbers($str) {
			$arr = preg_split("/(kymmentä|kymmenen|sataa|sata|tuhatta|tuhat|miljoonaa|miljoona)/", $str, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			$arrOfNumbers = array();
			foreach($arr as $key => $thisNumber) {
				$arrOfNumbers[$key] = $this->ConvertToNumber($thisNumber);
			}
			$multiplied = $this->Multiply($arrOfNumbers);
			$finalized = $this->Finalize($multiplied);
			return $finalized;
		}
	}
?>