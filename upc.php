<?php

class upc {

	private $height;
	private $width;
	private $barheight;
	private $barwidth;
	private $label;
	private $border;
	private $padding;
	private $mapcode;
	private $righmap;
	private $code;

	function __construct($label=null,$barwidth=2) {
		$this->set_width($barwidth);
		$this->set_label($label);

		$this->border = 2;

		$this->padding =  $barwidth*10;
		$this->mapcode = array(
			'0'=>'0001101', '1'=>'0011001',	'2'=>'0010011',	'3'=>'0111101',
			'4'=>'0100011', '5'=>'0110001', '6'=>'0101111', '7'=>'0111011',	
			'8'=>'0110111', '9'=>'0001011', '#'=>'01010', '*'=>'101'
		);
		$this->rightmap = array(
			'0'=>'1110010', '1'=>'1100110',	'2'=>'1101100',	'3'=>'1000010',
			'4'=>'1011100', '5'=>'1001110', '6'=>'1010000', '7'=>'1000100',	
			'8'=>'1001000', '9'=>'1110100', '#'=>'01010', '*'=>'101'
			);
	}
	public function set_width($barwidth) {
		if(is_int($barwidth)) {
			$this->barwidth = $barwidth;
		}
		else {
			$this->barwidth = 2;
		}
	}

	public function set_label($label) {
		if(is_null($label)) {
			$this->label = "Open Barcode";
		}
		else {
			$this->label = $label;
		}
	}

	public function embed($code) {
		ob_start();
		$this->build($code, true);
		$src = ob_get_contents();
		ob_end_clean();

		return 'data:image/png;base64,' . base64_encode($src);
	}

	public function build($code = null, $embed = false) {
		if(is_null($code)) {
			$this->code = '00000000000';
		}
		else {
			$this->code = $code;
		}

		$this->code = substr($this->code, 0, 11);

		if(is_numeric($this->code)) {
			$checksum = 0;

			for($digit = 0; $digit < strlen($this->code); $digit++) {
				if($digit%2 == 0) {
					$checksum += (int)$this->code[$digit] * 3;
				}
				else {
					$checksum += (int)$this->code[$digit];
				}
			}

			$checkdigit = 10 - $checksum % 10;

			$this->code .= $checkdigit;

			$code_display = $this->code;

			$this->code = "*".substr($this->code,0,6)."#".substr($this->code,6,6)."*";

			$this->width = $this->barwidth*95 + $this->padding*2;

			$this->barheight = $this->barwidth*95*0.75;

			$this->height = $this->barheight + $this->padding*2;

			$barcode = imagecreatetruecolor($this->width,$this->barheight);

			$black = imagecolorallocate($barcode,0,0,0);
			$white = imagecolorallocate($barcode,255,255,255);
			imagefill($barcode,0,0,$black);

			imagefilledrectangle($barcode,$this->border,$this->border,$this->width - $this->border - 1,$this->barheight - $this->border - 1, $white);

			$bar_position = $this->padding;

			for($count = 0; $count < 15; $count++) {
				$character = $this->code[$count];

				for($subcount = 0; $subcount < strlen($this->mapcode[$character]); $subcount++) {
					if($count < 7) {
						$color = ($this->mapcode[$character][$subcount] == "0") ? $white : $black;
					}
					else {
						$color = ($this->rightmap[$character][$subcount] == "0") ? $white : $black;
					}

					if(strlen($this->mapcode[$character]) == 7) {
						$height_offset = $this->height * 0.05;
					}
					else {
						$height_offset = 0;
					}

					imagefilledrectangle($barcode, $bar_position, $this->padding, $bar_position+$this->barwidth - 1, $this->barheight - $height_offset - $this->padding, $color);

					$bar_position += $this->barwidth;
				}
			}

			$font = "font/Aller.ttf";

			$digit1 = substr($code_display,0,1);
			$leftdigits = substr($code_display,1,5);
			$rightdigits = substr($code_display,6,5);
			$digit12 = substr($code_display,11,1);

			$outerdigitsscale = $this->barwidth*5;
			$innerdigitsscale = $this->barwidth/0.14;
			$labelscale = $this->barwidth/0.22;

			$digit1_xy = imagettfbbox($outerdigitsscale,0,$font,$digit1);
			$left_digits_xy = imagettfbbox($innerdigitsscale,0,$font,$leftdigits);
			$right_digits_xy = imagettfbbox($innerdigitsscale,0,$font,$rightdigits);
			$digit12_xy = imagettfbbox($outerdigitsscale,0,$font,$digit12);
			$label_xy = imagettfbbox($labelscale,0,$font,$this->label);

			$middleSpace = 43* $this->barwidth;

			$digit1_x = $this->border + ($this->padding - $digit1_xy[2] - $digit1_xy[0]) / 2;
			$left_digits_x = $this->border + $this->padding + $this->barwidth*3 + ($middleSpace - $left_digits_xy[2] - $left_digits_xy[0]) / 2;
			$right_digits_x = $this->border + $this->padding + $this->barwidth*6 + $middleSpace + ($middleSpace - $right_digits_xy[2] - $right_digits_xy[0]) / 2;
			$digit12_x = $this->border + $this->padding + $this->barwidth*6 + 2*$middleSpace + ($this->padding - $digit12_xy[2] - $digit12_xy[0]) / 2;
			$label_x = ($this->width - 2*$this->border - $label_xy[2] - $label_xy[0]) / 2;
			$label_y = ($this->padding - $label_xy[7] - $label_xy[1]) / 2;

			imagettftext($barcode, $outerdigitsscale, 0, $digit1_x, $this->barheight - ($this->padding/2) + $this->border, $black, $font, $digit1);
			imagettftext($barcode, $innerdigitsscale, 0, $left_digits_x, $this->barheight - ($this->padding/2) + $this->border, $black, $font, $leftdigits);
			imagettftext($barcode, $innerdigitsscale, 0, $right_digits_x, $this->barheight - ($this->padding/2) + $this->border, $black, $font, $rightdigits);
			imagettftext($barcode, $outerdigitsscale, 0, $digit12_x, $this->barheight - ($this->padding/2) + $this->border, $black, $font, $digit12);
			imagettftext($barcode, $labelscale, 0, $label_x, $label_y, $black, $font, $this->label);

			if(!$embed) {
				header("Content-type: image/png");
			}

			imagepng($barcode);
			imagedestroy($barcode);
		}
	}

}

?>