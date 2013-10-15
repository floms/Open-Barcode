<?php
/**
 * Copyright 2013 Floms, LLC (Yoel Nunez <y.nunez@developers.floms.com>)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

namespace OpenBarcode;


namespace OpenBarcode\Linear;

/**
 * Class LinearBarcode
 * @package OpenBarcode\Linear
 *
 * @author Yoel Nunez (y.nunez@developers.floms.com)
 */
interface LinearBarcode
{
    public function buildSequence();

    public function mapSequence($char, $pos);

    public function barcode();

    public function code();

    public function calculateCheckDigit();
}

/**
 * Class UPC
 * @package OpenBarcode\Linear
 *
 * @author Yoel Nunez (y.nunez@developers.floms.com)
 */
class UPC implements LinearBarcode
{
    private $bars = array();
    private $code = null;

    public function __construct($code)
    {
        if (strlen($code) != 11 and strlen($code) != 12) {
            throw new Exception("Invalid UPC code length");
        }

        $this->code = substr($code, 0, 11);
        $this->buildSequence();
    }

    public function buildSequence()
    {
        $this->code .= $this->calculateCheckDigit();

        $code = "*" . substr($this->code, 0, 6) . "#" . substr($this->code, 6, 6) . "*";

        for ($i = 0; $i < strlen($code); $i++) {
            $this->bars = array_merge($this->bars, $this->mapSequence($code[$i], $i));
        }
    }

    public function barcode()
    {
        return $this->bars;
    }

    public function mapSequence($char, $pos)
    {
        $sequence = array(
            "0" => array(0, 0, 0, 1, 1, 0, 1), "1" => array(0, 0, 1, 1, 0, 0, 1), "2" => array(0, 0, 1, 0, 0, 1, 1),
            "3" => array(0, 1, 1, 1, 1, 0, 1), "4" => array(0, 1, 0, 0, 0, 1, 1), "5" => array(0, 1, 1, 0, 0, 0, 1),
            "6" => array(0, 1, 0, 1, 1, 1, 1), "7" => array(0, 1, 1, 1, 0, 1, 1), "8" => array(0, 1, 1, 0, 1, 1, 1),
            "9" => array(0, 0, 0, 1, 0, 1, 1), "#" => array(0, 1, 0, 1, 0), "*" => array(1, 0, 1)
        );

        if ($pos >= 7) {
            $sequence["0"] = array(1, 1, 1, 0, 0, 1, 0);
            $sequence["1"] = array(1, 1, 0, 0, 1, 1, 0);
            $sequence["2"] = array(1, 1, 0, 1, 1, 0, 0);
            $sequence["3"] = array(1, 0, 0, 0, 0, 1, 0);
            $sequence["4"] = array(1, 0, 1, 1, 1, 0, 0);
            $sequence["5"] = array(1, 0, 0, 1, 1, 1, 0);
            $sequence["6"] = array(1, 0, 1, 0, 0, 0, 0);
            $sequence["7"] = array(1, 0, 0, 0, 1, 0, 0);
            $sequence["8"] = array(1, 0, 0, 1, 0, 0, 0);
            $sequence["9"] = array(1, 1, 1, 0, 1, 0, 0);
        }

        return $sequence[$char];
    }

    public function calculateCheckDigit()
    {
        $check_sum = 0;
        for ($i = 0; $i < 11; $i++) {
            $digit = (int)$this->code[$i];
            if ($i % 2 == 0)
                $check_sum += $digit * 3;
            else
                $check_sum += $digit;
        }

        $check_sum %= 10;

        if ($check_sum != 0)
            return 10 - $check_sum;
        else
            return $check_sum;
    }

	public function code(){
		return $this->code;
	}
}

/**
 * Class EAN
 * @package OpenBarcode\Linear
 *
 * @author Yoel Nunez (y.nunez@developers.floms.com)
 */
class EAN implements LinearBarcode
{

    private $bars = array();
    private $code = null;
    private $base = null;

    public function __construct($code)
    {
        if (strlen($code) != 12 && strlen($code) != 13) {
            throw new Exception("Invalid UPC code length");
        }

        $this->code = substr($code, 0, 12);
        $this->base = (int)$code[0];

        $this->buildSequence();
    }

    public function buildSequence()
    {
        $this->code .= $this->calculateCheckDigit();

        $code = "*" . substr($this->code, 1, 6) . "#" . substr($this->code, 7, 6) . "*";

        for ($i = 0; $i < strlen($code); $i++) {
            $this->bars = array_merge($this->bars, $this->mapSequence($code[$i], $i));
        }
    }

    public function barcode()
    {
        return $this->bars;
    }

    protected function codeL()
    {
        return array(
            "0" => array(0, 0, 0, 1, 1, 0, 1),
            "1" => array(0, 0, 1, 1, 0, 0, 1),
            "2" => array(0, 0, 1, 0, 0, 1, 1),
            "3" => array(0, 1, 1, 1, 1, 0, 1),
            "4" => array(0, 1, 0, 0, 0, 1, 1),
            "5" => array(0, 1, 1, 0, 0, 0, 1),
            "6" => array(0, 1, 0, 1, 1, 1, 1),
            "7" => array(0, 1, 1, 1, 0, 1, 1),
            "8" => array(0, 1, 1, 0, 1, 1, 1),
            "9" => array(0, 0, 0, 1, 0, 1, 1),
            "#" => array(0, 1, 0, 1, 0),
            "*" => array(1, 0, 1)
        );
    }

    protected function codeG()
    {
        $base = $this->codeL();

        $base["0"] = array(0, 1, 0, 0, 1, 1, 1);
        $base["1"] = array(0, 1, 1, 0, 0, 1, 1);
        $base["2"] = array(0, 0, 1, 1, 0, 1, 1);
        $base["3"] = array(0, 1, 0, 0, 0, 0, 1);
        $base["4"] = array(0, 0, 1, 1, 1, 0, 1);
        $base["5"] = array(0, 1, 1, 1, 0, 0, 1);
        $base["6"] = array(0, 0, 0, 0, 1, 0, 1);
        $base["7"] = array(0, 0, 1, 0, 0, 0, 1);
        $base["8"] = array(0, 0, 0, 1, 0, 0, 1);
        $base["9"] = array(0, 0, 1, 0, 1, 1, 1);

        return $base;
    }


    protected function codeR()
    {
        $base = $this->codeL();

        $base["0"] = array(1, 1, 1, 0, 0, 1, 0);
        $base["1"] = array(1, 1, 0, 0, 1, 1, 0);
        $base["2"] = array(1, 1, 0, 1, 1, 0, 0);
        $base["3"] = array(1, 0, 0, 0, 0, 1, 0);
        $base["4"] = array(1, 0, 1, 1, 1, 0, 0);
        $base["5"] = array(1, 0, 0, 1, 1, 1, 0);
        $base["6"] = array(1, 0, 1, 0, 0, 0, 0);
        $base["7"] = array(1, 0, 0, 0, 1, 0, 0);
        $base["8"] = array(1, 0, 0, 1, 0, 0, 0);
        $base["9"] = array(1, 1, 1, 0, 1, 0, 0);

        return $base;
    }


    public function mapSequence($char, $pos)
    {

        $b = $this->base;

        if ($pos > 6) {
            $sequence = $this->codeR();
        } else if ($b == 0) {
            $sequence = $this->codeL();
        } else if ($b == 1) {
            if ($pos == 1 || $pos == 2 || $pos == 4) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }
        } else if ($b == 2) {
            if ($pos == 1 || $pos == 2 || $pos == 5) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }

        } else if ($b == 3) {
            if ($pos == 1 || $pos == 2 || $pos == 6) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }
        } else if ($b == 4) {
            if ($pos == 1 || $pos == 3 || $pos == 4) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }
        } else if ($b == 5) {
            if ($pos == 1 || $pos == 4 || $pos == 5) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }
        } else if ($b == 6) {
            if ($pos == 1 || $pos == 5 || $pos == 6) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }
        } else if ($b == 7) {
            if ($pos == 1 || $pos == 3 || $pos == 5) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }
        } else if ($b == 8) {
            if ($pos == 1 || $pos == 3 || $pos == 6) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }
        } else {
            if ($pos == 1 || $pos == 4 || $pos == 6) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }
        }

        return $sequence[$char];
    }


    public function calculateCheckDigit()
    {
        $check_sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $digit = (int)$this->code[$i];
            if ($i % 2 == 1) {
                $check_sum += $digit * 3;
            } else {
                $check_sum += $digit;
            }

        }
        $check_sum %= 10;

        if ($check_sum != 0) {
            return 10 - $check_sum;
        } else {
            return $check_sum;
        }
    }

	public function code(){
		return $this->code;
	}
}

/**
 * Class Code39
 * @package OpenBarcode\Linear
 *
 * @author Yoel Nunez (y.nunez@developers.floms.com)
 */
class Code39 implements LinearBarcode
{

    private $bars = array();
    private $code = null;

    public function __construct($code)
    {
        $this->code = strtoupper($code);

        $this->buildSequence();
    }

    public function buildSequence()
    {
        $code = "*" . $this->code . "*";

        for ($i = 0; $i < strlen($code); $i++) {
            $this->bars = array_merge($this->bars, $this->mapSequence($code[$i], $i));
            $this->bars[] = 0;
        }
    }

    public function barcode()
    {
        return $this->bars;
    }

    public function mapSequence($char, $pos)
    {
        $sequence = array(
            "0" => array(1, 0, 1, 0, 0, 1, 1, 0, 1, 1, 0, 1),
            "1" => array(1, 1, 0, 1, 0, 0, 1, 0, 1, 0, 1, 1),
            "2" => array(1, 0, 1, 1, 0, 0, 1, 0, 1, 0, 1, 1),
            "3" => array(1, 1, 0, 1, 1, 0, 0, 1, 0, 1, 0, 1),
            "4" => array(1, 0, 1, 0, 0, 1, 1, 0, 1, 0, 1, 1),
            "5" => array(1, 1, 0, 1, 0, 0, 1, 1, 0, 1, 0, 1),
            "6" => array(1, 0, 1, 1, 0, 0, 1, 1, 0, 1, 0, 1),
            "7" => array(1, 0, 1, 0, 0, 1, 0, 1, 1, 0, 1, 1),
            "8" => array(1, 1, 0, 1, 0, 0, 1, 0, 1, 1, 0, 1),
            "9" => array(1, 0, 1, 1, 0, 0, 1, 0, 1, 1, 0, 1),
            "A" => array(1, 1, 0, 1, 0, 1, 0, 0, 1, 0, 1, 1),
            "B" => array(1, 0, 1, 1, 0, 1, 0, 0, 1, 0, 1, 1),
            "C" => array(1, 1, 0, 1, 1, 0, 1, 0, 0, 1, 0, 1),
            "D" => array(1, 0, 1, 0, 1, 1, 0, 0, 1, 0, 1, 1),
            "E" => array(1, 1, 0, 1, 0, 1, 1, 0, 0, 1, 0, 1),
            "F" => array(1, 0, 1, 1, 0, 1, 1, 0, 0, 1, 0, 1),
            "G" => array(1, 0, 1, 0, 1, 0, 0, 1, 1, 0, 1, 1),
            "H" => array(1, 1, 0, 1, 0, 1, 0, 0, 1, 1, 0, 1),
            "I" => array(1, 0, 1, 1, 0, 1, 0, 0, 1, 1, 0, 1),
            "J" => array(1, 0, 1, 0, 1, 1, 0, 0, 1, 1, 0, 1),
            "K" => array(1, 1, 0, 1, 0, 1, 0, 1, 0, 0, 1, 1),
            "L" => array(1, 0, 1, 1, 0, 1, 0, 1, 0, 0, 1, 1),
            "M" => array(1, 1, 0, 1, 1, 0, 1, 0, 1, 0, 0, 1),
            "N" => array(1, 0, 1, 0, 1, 1, 0, 1, 0, 0, 1, 1),
            "O" => array(1, 1, 0, 1, 0, 1, 1, 0, 1, 0, 0, 1),
            "P" => array(1, 0, 1, 1, 0, 1, 1, 0, 1, 0, 0, 1),
            "Q" => array(1, 0, 1, 0, 1, 0, 1, 1, 0, 0, 1, 1),
            "R" => array(1, 1, 0, 1, 0, 1, 0, 1, 1, 0, 0, 1),
            "S" => array(1, 0, 1, 1, 0, 1, 0, 1, 1, 0, 0, 1),
            "T" => array(1, 0, 1, 0, 1, 1, 0, 1, 1, 0, 0, 1),
            "U" => array(1, 1, 0, 0, 1, 0, 1, 0, 1, 0, 1, 1),
            "V" => array(1, 0, 0, 1, 1, 0, 1, 0, 1, 0, 1, 1),
            "W" => array(1, 1, 0, 0, 1, 1, 0, 1, 0, 1, 0, 1),
            "X" => array(1, 0, 0, 1, 0, 1, 1, 0, 1, 0, 1, 1),
            "Y" => array(1, 1, 0, 0, 1, 0, 1, 1, 0, 1, 0, 1),
            "Z" => array(1, 0, 0, 1, 1, 0, 1, 1, 0, 1, 0, 1),
            "-" => array(1, 0, 0, 1, 0, 1, 0, 1, 1, 0, 1, 1),
            "." => array(1, 1, 0, 0, 1, 0, 1, 0, 1, 1, 0, 1),
            " " => array(1, 0, 0, 1, 1, 0, 1, 0, 1, 1, 0, 1),
            "$" => array(1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 1),
            "/" => array(1, 0, 0, 1, 0, 0, 1, 0, 1, 0, 0, 1),
            "+" => array(1, 0, 0, 1, 0, 1, 0, 0, 1, 0, 0, 1),
            "%" => array(1, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 1),
            "*" => array(1, 0, 0, 1, 0, 1, 1, 0, 1, 1, 0, 1),
        );

        return $sequence[$char];
    }


    public function calculateCheckDigit()
    {
        return 0;
    }

	public function code(){
		return $this->code;
	}
}
