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

window.OpenBarcode = (function () {

    var OpenBarcode = OpenBarcode || {};

    /**
     * Class LinearBarcode
     *
     * @constructor
     *
     * @author Yoel Nunez (y.nunez@developers.floms.com)
     */
    OpenBarcode.LinearBarCode = function () {
        this.buildSequence = function () {
        };
        this.mapSequence = function (char, pos) {
        };
        this.barcode = function () {
        };
        this.calculateCheckDigit = function () {
        };
		this.code = function () {
        };
    };

    /**
     * Class UPC
     *
     * @param code
     * @constructor
     *
     * @author Yoel Nunez (y.nunez@developers.floms.com)
     */
    OpenBarcode.UPC = function (code) {
        this.bars = new Array();

        if (code.length != 11 && code.length != 12) {
            throw new function () {
                this.message = "Invalid Barcode Length";
                this.name = "Exception";
            };
        }

        this.prototype = new OpenBarcode.LinearBarCode()

        this.__code = code.substr(0, 11);

        this.buildSequence();
    };

    OpenBarcode.UPC.prototype.buildSequence = function () {
        this.__code += this.calculateCheckDigit();

        var code = "*" + this.__code.substr(0, 6) + "#" + this.__code.substr(6, 6) + "*";

        for (var i = 0; i < code.length; i++) {
            this.bars = this.bars.concat(this.mapSequence(code[i], i));
        }
    };

    OpenBarcode.UPC.prototype.barcode = function () {
        return this.bars;
    };
	
	OpenBarcode.UPC.prototype.code = function () {
        return this.__code;
    };

    OpenBarcode.UPC.prototype.mapSequence = function (char, pos) {

        var sequence = {
            "0": [0, 0, 0, 1, 1, 0, 1], "1": [0, 0, 1, 1, 0, 0, 1], "2": [0, 0, 1, 0, 0, 1, 1],
            "3": [0, 1, 1, 1, 1, 0, 1], "4": [0, 1, 0, 0, 0, 1, 1], "5": [0, 1, 1, 0, 0, 0, 1],
            "6": [0, 1, 0, 1, 1, 1, 1], "7": [0, 1, 1, 1, 0, 1, 1], "8": [0, 1, 1, 0, 1, 1, 1],
            "9": [0, 0, 0, 1, 0, 1, 1], "#": [0, 1, 0, 1, 0], "*": [1, 0, 1]
        };

        if (pos >= 7) {
            sequence["0"] = [1, 1, 1, 0, 0, 1, 0];
            sequence["1"] = [1, 1, 0, 0, 1, 1, 0];
            sequence["2"] = [1, 1, 0, 1, 1, 0, 0];
            sequence["3"] = [1, 0, 0, 0, 0, 1, 0];
            sequence["4"] = [1, 0, 1, 1, 1, 0, 0];
            sequence["5"] = [1, 0, 0, 1, 1, 1, 0];
            sequence["6"] = [1, 0, 1, 0, 0, 0, 0];
            sequence["7"] = [1, 0, 0, 0, 1, 0, 0];
            sequence["8"] = [1, 0, 0, 1, 0, 0, 0];
            sequence["9"] = [1, 1, 1, 0, 1, 0, 0];
        }

        return sequence[char];
    };


    OpenBarcode.UPC.prototype.calculateCheckDigit = function () {
        var check_sum = 0;
        for (i = 0; i < 11; i++) {
            digit = parseInt(this.__code[i]);
            if (i % 2 == 0) {
                check_sum += digit * 3;
            }
            else {
                check_sum += digit;
            }
        }

        check_sum %= 10;

        return (check_sum != 0) ? 10 - check_sum : check_sum;
    };

    /**
     * Class EAN
     *
     * @param code
     * @constructor
     *
     * @author Yoel Nunez (y.nunez@developers.floms.com)
     */
    OpenBarcode.EAN = function (code) {
        this.bars = new Array();
        this.base = 0;

        if (code.length != 12 && code.length != 13) {
            throw new function () {

            };
        }

        this.prototype = new OpenBarcode.LinearBarCode();

        this.__code = code.substr(0, 12);

        this.base = parseInt(code[0]);

        this.buildSequence();
    };

    OpenBarcode.EAN.prototype.buildSequence = function () {
        this.__code += this.calculateCheckDigit();

        var code = "*" + this.__code.substr(1, 6) + "#" + this.__code.substr(7, 6) + "*";

        for (i = 0; i < code.length; i++) {
            this.bars = this.bars.concat(this.mapSequence(code[i], i));
        }
    };

    OpenBarcode.EAN.prototype.barcode = function () {
        return this.bars;
    };

	OpenBarcode.EAN.prototype.code = function () {
        return this.__code;
    };
	
    OpenBarcode.EAN.prototype.codeL = function codeL() {
        return {
            "0": [0, 0, 0, 1, 1, 0, 1],
            "1": [0, 0, 1, 1, 0, 0, 1],
            "2": [0, 0, 1, 0, 0, 1, 1],
            "3": [0, 1, 1, 1, 1, 0, 1],
            "4": [0, 1, 0, 0, 0, 1, 1],
            "5": [0, 1, 1, 0, 0, 0, 1],
            "6": [0, 1, 0, 1, 1, 1, 1],
            "7": [0, 1, 1, 1, 0, 1, 1],
            "8": [0, 1, 1, 0, 1, 1, 1],
            "9": [0, 0, 0, 1, 0, 1, 1],
            "#": [0, 1, 0, 1, 0],
            "*": [1, 0, 1]
        };
    };

    OpenBarcode.EAN.prototype.codeG = function () {
        var base = this.__codeL();

        base["0"] = [0, 1, 0, 0, 1, 1, 1];
        base["1"] = [0, 1, 1, 0, 0, 1, 1];
        base["2"] = [0, 0, 1, 1, 0, 1, 1];
        base["3"] = [0, 1, 0, 0, 0, 0, 1];
        base["4"] = [0, 0, 1, 1, 1, 0, 1];
        base["5"] = [0, 1, 1, 1, 0, 0, 1];
        base["6"] = [0, 0, 0, 0, 1, 0, 1];
        base["7"] = [0, 0, 1, 0, 0, 0, 1];
        base["8"] = [0, 0, 0, 1, 0, 0, 1];
        base["9"] = [0, 0, 1, 0, 1, 1, 1];

        return base;
    };


    OpenBarcode.EAN.prototype.codeR = function () {
        var base = this.__codeL();

        base["0"] = [1, 1, 1, 0, 0, 1, 0];
        base["1"] = [1, 1, 0, 0, 1, 1, 0];
        base["2"] = [1, 1, 0, 1, 1, 0, 0];
        base["3"] = [1, 0, 0, 0, 0, 1, 0];
        base["4"] = [1, 0, 1, 1, 1, 0, 0];
        base["5"] = [1, 0, 0, 1, 1, 1, 0];
        base["6"] = [1, 0, 1, 0, 0, 0, 0];
        base["7"] = [1, 0, 0, 0, 1, 0, 0];
        base["8"] = [1, 0, 0, 1, 0, 0, 0];
        base["9"] = [1, 1, 1, 0, 1, 0, 0];

        return base;
    };


    OpenBarcode.EAN.prototype.mapSequence = function (char, pos) {

        var sequence = {};

        var b = this.base;

        if (pos > 6) {
            sequence = this.__codeR();
        } else if (b == 0) {
            sequence = this.__codeL();
        } else if (b == 1) {
            if (pos == 1 || pos == 2 || pos == 4) {
                sequence = this.__codeL();
            } else {
                sequence = this.__codeG();
            }
        } else if (b == 2) {
            if (pos == 1 || pos == 2 || pos == 5) {
                sequence = this.__codeL();
            } else {
                sequence = this.__codeG();
            }

        } else if (b == 3) {
            if (pos == 1 || pos == 2 || pos == 6) {
                sequence = this.__codeL();
            } else {
                sequence = this.__codeG();
            }
        } else if (b == 4) {
            if (pos == 1 || pos == 3 || pos == 4) {
                sequence = this.__codeL();
            } else {
                sequence = this.__codeG();
            }
        } else if (b == 5) {
            if (pos == 1 || pos == 4 || pos == 5) {
                sequence = this.__codeL();
            } else {
                sequence = this.__codeG();
            }
        } else if (b == 6) {
            if (pos == 1 || pos == 5 || pos == 6) {
                sequence = this.__codeL();
            } else {
                sequence = this.__codeG();
            }
        } else if (b == 7) {
            if (pos == 1 || pos == 3 || pos == 5) {
                sequence = this.__codeL();
            } else {
                sequence = this.__codeG();
            }
        } else if (b == 8) {
            if (pos == 1 || pos == 3 || pos == 6) {
                sequence = this.__codeL();
            } else {
                sequence = this.__codeG();
            }
        } else {
            if (pos == 1 || pos == 4 || pos == 6) {
                sequence = this.__codeL();
            } else {
                sequence = this.__codeG();
            }
        }

        return sequence[char];
    };


    OpenBarcode.EAN.prototype.calculateCheckDigit = function () {
        var check_sum = 0;
        for (i = 0; i < 12; i++) {
            var digit = parseInt(this.__code[i]);
            if (i % 2 == 1) {
                check_sum += digit * 3;
            } else {
                check_sum += digit;
            }

        }
        check_sum %= 10;

        return (check_sum != 0) ? 10 - check_sum : check_sum;
    };


    /**
     *
     * Class Code39
     * @param code
     * @constructor
     *
     * @author Yoel Nunez (y.nunez@developers.floms.com)
     */
    OpenBarcode.Code39 = function (code) {
        this.bars = new Array();

        this.prototype = new OpenBarcode.LinearBarCode();

        this.__code = code.toUpperCase();
        this.buildSequence();
    };

    OpenBarcode.Code39.prototype.buildSequence = function () {
        var code = "*" + this.__code + "*";

        for (i = 0; i < code.length; i++) {
            this.bars = this.bars.concat(this.mapSequence(code[i], i).concat([0]));
        }
    };

    OpenBarcode.Code39.prototype.barcode = function () {
        return this.bars;
    };
	
	OpenBarcode.Code39.prototype.code = function () {
        return this.__code;
    };

    OpenBarcode.Code39.prototype.mapSequence = function (char, pos) {
        var sequence = {
            "0": [1, 0, 1, 0, 0, 1, 1, 0, 1, 1, 0, 1],
            "1": [1, 1, 0, 1, 0, 0, 1, 0, 1, 0, 1, 1],
            "2": [1, 0, 1, 1, 0, 0, 1, 0, 1, 0, 1, 1],
            "3": [1, 1, 0, 1, 1, 0, 0, 1, 0, 1, 0, 1],
            "4": [1, 0, 1, 0, 0, 1, 1, 0, 1, 0, 1, 1],
            "5": [1, 1, 0, 1, 0, 0, 1, 1, 0, 1, 0, 1],
            "6": [1, 0, 1, 1, 0, 0, 1, 1, 0, 1, 0, 1],
            "7": [1, 0, 1, 0, 0, 1, 0, 1, 1, 0, 1, 1],
            "8": [1, 1, 0, 1, 0, 0, 1, 0, 1, 1, 0, 1],
            "9": [1, 0, 1, 1, 0, 0, 1, 0, 1, 1, 0, 1],
            "A": [1, 1, 0, 1, 0, 1, 0, 0, 1, 0, 1, 1],
            "B": [1, 0, 1, 1, 0, 1, 0, 0, 1, 0, 1, 1],
            "C": [1, 1, 0, 1, 1, 0, 1, 0, 0, 1, 0, 1],
            "D": [1, 0, 1, 0, 1, 1, 0, 0, 1, 0, 1, 1],
            "E": [1, 1, 0, 1, 0, 1, 1, 0, 0, 1, 0, 1],
            "F": [1, 0, 1, 1, 0, 1, 1, 0, 0, 1, 0, 1],
            "G": [1, 0, 1, 0, 1, 0, 0, 1, 1, 0, 1, 1],
            "H": [1, 1, 0, 1, 0, 1, 0, 0, 1, 1, 0, 1],
            "I": [1, 0, 1, 1, 0, 1, 0, 0, 1, 1, 0, 1],
            "J": [1, 0, 1, 0, 1, 1, 0, 0, 1, 1, 0, 1],
            "K": [1, 1, 0, 1, 0, 1, 0, 1, 0, 0, 1, 1],
            "L": [1, 0, 1, 1, 0, 1, 0, 1, 0, 0, 1, 1],
            "M": [1, 1, 0, 1, 1, 0, 1, 0, 1, 0, 0, 1],
            "N": [1, 0, 1, 0, 1, 1, 0, 1, 0, 0, 1, 1],
            "O": [1, 1, 0, 1, 0, 1, 1, 0, 1, 0, 0, 1],
            "P": [1, 0, 1, 1, 0, 1, 1, 0, 1, 0, 0, 1],
            "Q": [1, 0, 1, 0, 1, 0, 1, 1, 0, 0, 1, 1],
            "R": [1, 1, 0, 1, 0, 1, 0, 1, 1, 0, 0, 1],
            "S": [1, 0, 1, 1, 0, 1, 0, 1, 1, 0, 0, 1],
            "T": [1, 0, 1, 0, 1, 1, 0, 1, 1, 0, 0, 1],
            "U": [1, 1, 0, 0, 1, 0, 1, 0, 1, 0, 1, 1],
            "V": [1, 0, 0, 1, 1, 0, 1, 0, 1, 0, 1, 1],
            "W": [1, 1, 0, 0, 1, 1, 0, 1, 0, 1, 0, 1],
            "X": [1, 0, 0, 1, 0, 1, 1, 0, 1, 0, 1, 1],
            "Y": [1, 1, 0, 0, 1, 0, 1, 1, 0, 1, 0, 1],
            "Z": [1, 0, 0, 1, 1, 0, 1, 1, 0, 1, 0, 1],
            "-": [1, 0, 0, 1, 0, 1, 0, 1, 1, 0, 1, 1],
            ".": [1, 1, 0, 0, 1, 0, 1, 0, 1, 1, 0, 1],
            " ": [1, 0, 0, 1, 1, 0, 1, 0, 1, 1, 0, 1],
            "$": [1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 1],
            "/": [1, 0, 0, 1, 0, 0, 1, 0, 1, 0, 0, 1],
            "+": [1, 0, 0, 1, 0, 1, 0, 0, 1, 0, 0, 1],
            "%": [1, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 1],
            "*": [1, 0, 0, 1, 0, 1, 1, 0, 1, 1, 0, 1]
        };

        return sequence[char];
    };


    OpenBarcode.Code39.prototype.calculateCheckDigit = function () {
        return 0;
    };


    return OpenBarcode;

})();

