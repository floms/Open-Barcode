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
package com.floms.openbarcode;

import java.util.HashMap;

public class EAN implements LinearBarcode {
    private String code;
    private int base;
    private int[] bars;

    public EAN(String code) throws Exception {
        if (code.length() != 12 && code.length() != 13) {
            throw new Exception("Invalid EAN13 code length");
        }

        this.code = code.substring(0, 12);

        this.base = Integer.valueOf(code.charAt(0));

        bars = new int[95];
        this.buildSequence();
    }

    protected HashMap<Character, int[]> codeL() {
        HashMap<Character, int[]> sequence = new HashMap<Character, int[]>();

        sequence.put('0', new int[]{0, 0, 0, 1, 1, 0, 1});
        sequence.put('1', new int[]{0, 0, 1, 1, 0, 0, 1});
        sequence.put('2', new int[]{0, 0, 1, 0, 0, 1, 1});
        sequence.put('3', new int[]{0, 1, 1, 1, 1, 0, 1});
        sequence.put('4', new int[]{0, 1, 0, 0, 0, 1, 1});
        sequence.put('5', new int[]{0, 1, 1, 0, 0, 0, 1});
        sequence.put('6', new int[]{0, 1, 0, 1, 1, 1, 1});
        sequence.put('7', new int[]{0, 1, 1, 1, 0, 1, 1});
        sequence.put('8', new int[]{0, 1, 1, 0, 1, 1, 1});
        sequence.put('9', new int[]{0, 0, 0, 1, 0, 1, 1});
        sequence.put('#', new int[]{0, 1, 0, 1, 0});
        sequence.put('*', new int[]{1, 0, 1});

        return sequence;
    }

    protected HashMap<Character, int[]> codeG() {
        HashMap<Character, int[]> sequence = codeL();

        sequence.put('0', new int[]{0, 1, 0, 0, 1, 1, 1});
        sequence.put('1', new int[]{0, 1, 1, 0, 0, 1, 1});
        sequence.put('2', new int[]{0, 0, 1, 1, 0, 1, 1});
        sequence.put('3', new int[]{0, 1, 0, 0, 0, 0, 1});
        sequence.put('4', new int[]{0, 0, 1, 1, 1, 0, 1});
        sequence.put('5', new int[]{0, 1, 1, 1, 0, 0, 1});
        sequence.put('6', new int[]{0, 0, 0, 0, 1, 0, 1});
        sequence.put('7', new int[]{0, 0, 1, 0, 0, 0, 1});
        sequence.put('8', new int[]{0, 0, 0, 1, 0, 0, 1});
        sequence.put('9', new int[]{0, 0, 1, 0, 1, 1, 1});

        return sequence;
    }

    protected HashMap<Character, int[]> codeR() {
        HashMap<Character, int[]> sequence = codeL();

        sequence.put('0', new int[]{1, 1, 1, 0, 0, 1, 0});
        sequence.put('1', new int[]{1, 1, 0, 0, 1, 1, 0});
        sequence.put('2', new int[]{1, 1, 0, 1, 1, 0, 0});
        sequence.put('3', new int[]{1, 0, 0, 0, 0, 1, 0});
        sequence.put('4', new int[]{1, 0, 1, 1, 1, 0, 0});
        sequence.put('5', new int[]{1, 0, 0, 1, 1, 1, 0});
        sequence.put('6', new int[]{1, 0, 1, 0, 0, 0, 0});
        sequence.put('7', new int[]{1, 0, 0, 0, 1, 0, 0});
        sequence.put('8', new int[]{1, 0, 0, 1, 0, 0, 0});
        sequence.put('9', new int[]{1, 1, 1, 0, 1, 0, 0});

        return sequence;
    }

    @Override
    public int[] mapSequence(char c, int pos) {
        int b = this.base;

        HashMap<Character, int[]> sequence;

        if (pos > 6) {
            sequence = codeR();
        } else if (b == 0) {
            sequence = codeL();
        } else if (b == 1) {
            if (pos == 1 || pos == 2 || pos == 4) {
                sequence = codeL();
            } else {
                sequence = codeG();
            }
        } else if (b == 2) {
            if (pos == 1 || pos == 2 || pos == 5) {
                sequence = codeL();
            } else {
                sequence = codeG();
            }

        } else if (b == 3) {
            if (pos == 1 || pos == 2 || pos == 6) {
                sequence = codeL();
            } else {
                sequence = codeG();
            }
        } else if (b == 4) {
            if (pos == 1 || pos == 3 || pos == 4) {
                sequence = codeL();
            } else {
                sequence = codeG();
            }
        } else if (b == 5) {
            if (pos == 1 || pos == 4 || pos == 5) {
                sequence = codeL();
            } else {
                sequence = codeG();
            }
        } else if (b == 6) {
            if (pos == 1 || pos == 5 || pos == 6) {
                sequence = codeL();
            } else {
                sequence = codeG();
            }
        } else if (b == 7) {
            if (pos == 1 || pos == 3 || pos == 5) {
                sequence = codeL();
            } else {
                sequence = codeG();
            }
        } else if (b == 8) {
            if (pos == 1 || pos == 3 || pos == 6) {
                sequence = codeL();
            } else {
                sequence = codeG();
            }
        } else {
            if (pos == 1 || pos == 4 || pos == 6) {
                sequence = codeL();
            } else {
                sequence = codeG();
            }
        }

        return sequence.get(c);
    }

    @Override
    public void buildSequence() {
        code += calculateCheckDigit();

        String encoded = "*" + code.substring(1, 7) + "#" + code.substring(7, 13) + "*";

        int p = 0;
        for (int i = 0; i < encoded.length(); i++) {
            int[] sequence = mapSequence(encoded.charAt(i), i);
            System.arraycopy(sequence, 0, bars, p, sequence.length);
            p += sequence.length;
        }
    }

    @Override
    public int[] barcode() {
        return bars;
    }

    @Override
    public String code() {
        return code;
    }

    @Override
    public int calculateCheckDigit() {
        int checksum = 0;

        for (int i = 0; i < 12; i++) {
            int digit = Integer.valueOf(code.charAt(i));

            if (i % 2 == 1) {
                checksum += digit * 3;
            } else {
                checksum += digit;
            }
        }

        checksum %= 10;

        return checksum != 0 ? 10 - checksum : checksum;
    }
}
