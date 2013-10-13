#
# Copyright 2013 Floms, LLC (Yoel Nunez <y.nunez@developers.floms.com>)
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
# http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
#
#

__author__ = 'Yoel Nunez <y.nunez@developers.floms.com>'


from abc import ABCMeta, abstractmethod


class LinearBarcode:
    __metaclass__ = ABCMeta

    code = None
    bars = []

    @abstractmethod
    def build_sequence(self):
        pass

    @abstractmethod
    def barcode(self):
        pass

    @abstractmethod
    def calculate_check_digit(self):
        pass

    @abstractmethod
    def map_sequence(self, char, pos):
        pass


class UPC(LinearBarcode):
    def __init__(self, code):
        if len(code) != 11 and len(code) != 12:
            raise Exception("Invalid UPC code length")

        self.code = code[:11]

        self.build_sequence()

    def build_sequence(self):
        self.code += str(self.calculate_check_digit())

        code = "*" + self.code[0:6] + "#" + self.code[6:12] + "*"

        for p in range(len(code)):
            self.bars[len(self.bars):] = self.map_sequence(code[p:p + 1], p)

    def barcode(self):
        return self.bars

    def map_sequence(self, char, pos):
        sequence = {
            "0": [0, 0, 0, 1, 1, 0, 1], "1": [0, 0, 1, 1, 0, 0, 1], "2": [0, 0, 1, 0, 0, 1, 1],
            "3": [0, 1, 1, 1, 1, 0, 1], "4": [0, 1, 0, 0, 0, 1, 1], "5": [0, 1, 1, 0, 0, 0, 1],
            "6": [0, 1, 0, 1, 1, 1, 1], "7": [0, 1, 1, 1, 0, 1, 1], "8": [0, 1, 1, 0, 1, 1, 1],
            "9": [0, 0, 0, 1, 0, 1, 1], "#": [0, 1, 0, 1, 0], "*": [1, 0, 1]
        }

        if pos >= 7:
            sequence["0"] = [1, 1, 1, 0, 0, 1, 0]
            sequence["1"] = [1, 1, 0, 0, 1, 1, 0]
            sequence["2"] = [1, 1, 0, 1, 1, 0, 0]
            sequence["3"] = [1, 0, 0, 0, 0, 1, 0]
            sequence["4"] = [1, 0, 1, 1, 1, 0, 0]
            sequence["5"] = [1, 0, 0, 1, 1, 1, 0]
            sequence["6"] = [1, 0, 1, 0, 0, 0, 0]
            sequence["7"] = [1, 0, 0, 0, 1, 0, 0]
            sequence["8"] = [1, 0, 0, 1, 0, 0, 0]
            sequence["9"] = [1, 1, 1, 0, 1, 0, 0]

        return sequence[char]

    def calculate_check_digit(self):
        check_sum = 0
        for i in range(11):
            digit = int(self.code[i:i + 1])
            if i % 2 == 0:
                check_sum += digit * 3
            else:
                check_sum += digit

        check_sum %= 10

        if check_sum != 0:
            return 10 - check_sum
        else:
            return check_sum


class EAN(LinearBarcode):
    base = 0

    def __init__(self, code):
        if len(code) != 12 and len(code) != 13:
            raise Exception("Invalid UPC code length")

        self.code = code[:12]
        self.base = int(code[:1])

        self.build_sequence()

    def build_sequence(self):
        self.code += str(self.calculate_check_digit())

        code = "*" + self.code[1:7] + "#" + self.code[7:13] + "*"

        for p in range(len(code)):
            self.bars[len(self.bars):] = self.map_sequence(code[p:p + 1], p)

    def barcode(self):
        return self.bars

    def code_l(self):
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
        }

    def code_g(self):
        base = self.code_l()

        base["0"] = [0, 1, 0, 0, 1, 1, 1]
        base["1"] = [0, 1, 1, 0, 0, 1, 1]
        base["2"] = [0, 0, 1, 1, 0, 1, 1]
        base["3"] = [0, 1, 0, 0, 0, 0, 1]
        base["4"] = [0, 0, 1, 1, 1, 0, 1]
        base["5"] = [0, 1, 1, 1, 0, 0, 1]
        base["6"] = [0, 0, 0, 0, 1, 0, 1]
        base["7"] = [0, 0, 1, 0, 0, 0, 1]
        base["8"] = [0, 0, 0, 1, 0, 0, 1]
        base["9"] = [0, 0, 1, 0, 1, 1, 1]

        return base


    def code_r(self):
        base = self.code_l()

        base["0"] = [1, 1, 1, 0, 0, 1, 0]
        base["1"] = [1, 1, 0, 0, 1, 1, 0]
        base["2"] = [1, 1, 0, 1, 1, 0, 0]
        base["3"] = [1, 0, 0, 0, 0, 1, 0]
        base["4"] = [1, 0, 1, 1, 1, 0, 0]
        base["5"] = [1, 0, 0, 1, 1, 1, 0]
        base["6"] = [1, 0, 1, 0, 0, 0, 0]
        base["7"] = [1, 0, 0, 0, 1, 0, 0]
        base["8"] = [1, 0, 0, 1, 0, 0, 0]
        base["9"] = [1, 1, 1, 0, 1, 0, 0]

        return base


    def map_sequence(self, char, pos):

        sequence = {}
        b = self.base

        if pos > 6:
            sequence = self.code_r()
        elif b == 0:
            sequence = self.code_l()
        elif b == 1:
            if pos == 1 or pos == 2 or pos == 4:
                sequence = self.code_l()
            else:
                sequence = self.code_g()
        elif b == 2:
            if pos == 1 or pos == 2 or pos == 5:
                sequence = self.code_l()
            else:
                sequence = self.code_g()
        elif b == 3:
            if pos == 1 or pos == 2 or pos == 6:
                sequence = self.code_l()
            else:
                sequence = self.code_g()
        elif b == 4:
            if pos == 1 or pos == 3 or pos == 4:
                sequence = self.code_l()
            else:
                sequence = self.code_g()
        elif b == 5:
            if pos == 1 or pos == 4 or pos == 5:
                sequence = self.code_l()
            else:
                sequence = self.code_g()
        elif b == 6:
            if pos == 1 or pos == 5 or pos == 6:
                sequence = self.code_l()
            else:
                sequence = self.code_g()
        elif b == 7:
            if pos == 1 or pos == 3 or pos == 5:
                sequence = self.code_l()
            else:
                sequence = self.code_g()
        elif b == 8:
            if pos == 1 or pos == 3 or pos == 6:
                sequence = self.code_l()
            else:
                sequence = self.code_g()
        else:
            if pos == 1 or pos == 4 or pos == 6:
                sequence = self.code_l()
            else:
                sequence = self.code_g()

        return sequence[char]


    def calculate_check_digit(self):
        check_sum = 0
        for i in range(12):
            digit = int(self.code[i:i + 1])
            if i % 2 == 1:
                check_sum += digit * 3
            else:
                check_sum += digit

        check_sum %= 10

        if check_sum != 0:
            return 10 - check_sum
        else:
            return check_sum


class Code39(LinearBarcode):
    def __init__(self, code):
        self.code = str(code).upper()

        self.build_sequence()

    def build_sequence(self):
        code = "*" + self.code + "*"

        for p in range(len(code)):
            self.bars[len(self.bars):] = self.map_sequence(code[p:p + 1], p)
            self.bars.append(0)

    def barcode(self):
        return self.bars

    def map_sequence(self, char, pos):
        sequence = {
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
            "*": [1, 0, 0, 1, 0, 1, 1, 0, 1, 1, 0, 1],
        }

        return sequence[char]


    def calculate_check_digit(self):
        return 0
