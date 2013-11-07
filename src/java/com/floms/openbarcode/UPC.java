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

public class UPC implements LinearBarcode {
    private int[] bars;
    private String code;

    public UPC(String code) throws Exception {
        if (code.length() != 11 && code.length() != 12) {
            throw new Exception("Invalid UPC code length");
        }

        this.code = code.substring(0, 11);

        bars = new int[95];
        this.buildSequence();
    }

    @Override
    public void buildSequence() {
        code += calculateCheckDigit();

        String encoded = "*" + code.substring(0, 6) + "#" + code.substring(6, 12) + "*";


        int p = 0;
        for (int i = 0; i < encoded.length(); i++) {
            int[] sequence = mapSequence(encoded.charAt(i), i);
            System.arraycopy(sequence, 0, bars, p, sequence.length);
            p += sequence.length;
        }
    }

    @Override
    public int[] mapSequence(char c, int pos) {
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

        if (pos >= 7) {
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
        }

        return sequence.get(c);
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

        for (int i = 0; i < 11; i++) {
            int digit = Integer.valueOf(code.charAt(i));

            if (i % 2 == 0) {
                checksum += digit * 3;
            } else {
                checksum += digit;
            }
        }

        checksum %= 10;

        return checksum != 0 ? 10 - checksum : checksum;
    }
}
