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

public class Code39 implements LinearBarcode {
    private String code;
    private int[] bars;

    public Code39(String code) {
        this.code = code.toUpperCase().trim();

        bars = new int[(code.length() + 2) * 12];

        this.buildSequence();
    }

    @Override
    public void buildSequence() {
        String encoded = "*" + code + "*";

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

        sequence.put('0', new int[]{1, 0, 1, 0, 0, 1, 1, 0, 1, 1, 0, 1});
        sequence.put('1', new int[]{1, 1, 0, 1, 0, 0, 1, 0, 1, 0, 1, 1});
        sequence.put('2', new int[]{1, 0, 1, 1, 0, 0, 1, 0, 1, 0, 1, 1});
        sequence.put('3', new int[]{1, 1, 0, 1, 1, 0, 0, 1, 0, 1, 0, 1});
        sequence.put('4', new int[]{1, 0, 1, 0, 0, 1, 1, 0, 1, 0, 1, 1});
        sequence.put('5', new int[]{1, 1, 0, 1, 0, 0, 1, 1, 0, 1, 0, 1});
        sequence.put('6', new int[]{1, 0, 1, 1, 0, 0, 1, 1, 0, 1, 0, 1});
        sequence.put('7', new int[]{1, 0, 1, 0, 0, 1, 0, 1, 1, 0, 1, 1});
        sequence.put('8', new int[]{1, 1, 0, 1, 0, 0, 1, 0, 1, 1, 0, 1});
        sequence.put('9', new int[]{1, 0, 1, 1, 0, 0, 1, 0, 1, 1, 0, 1});
        sequence.put('A', new int[]{1, 1, 0, 1, 0, 1, 0, 0, 1, 0, 1, 1});
        sequence.put('B', new int[]{1, 0, 1, 1, 0, 1, 0, 0, 1, 0, 1, 1});
        sequence.put('C', new int[]{1, 1, 0, 1, 1, 0, 1, 0, 0, 1, 0, 1});
        sequence.put('D', new int[]{1, 0, 1, 0, 1, 1, 0, 0, 1, 0, 1, 1});
        sequence.put('E', new int[]{1, 1, 0, 1, 0, 1, 1, 0, 0, 1, 0, 1});
        sequence.put('F', new int[]{1, 0, 1, 1, 0, 1, 1, 0, 0, 1, 0, 1});
        sequence.put('G', new int[]{1, 0, 1, 0, 1, 0, 0, 1, 1, 0, 1, 1});
        sequence.put('H', new int[]{1, 1, 0, 1, 0, 1, 0, 0, 1, 1, 0, 1});
        sequence.put('I', new int[]{1, 0, 1, 1, 0, 1, 0, 0, 1, 1, 0, 1});
        sequence.put('J', new int[]{1, 0, 1, 0, 1, 1, 0, 0, 1, 1, 0, 1});
        sequence.put('K', new int[]{1, 1, 0, 1, 0, 1, 0, 1, 0, 0, 1, 1});
        sequence.put('L', new int[]{1, 0, 1, 1, 0, 1, 0, 1, 0, 0, 1, 1});
        sequence.put('M', new int[]{1, 1, 0, 1, 1, 0, 1, 0, 1, 0, 0, 1});
        sequence.put('N', new int[]{1, 0, 1, 0, 1, 1, 0, 1, 0, 0, 1, 1});
        sequence.put('O', new int[]{1, 1, 0, 1, 0, 1, 1, 0, 1, 0, 0, 1});
        sequence.put('P', new int[]{1, 0, 1, 1, 0, 1, 1, 0, 1, 0, 0, 1});
        sequence.put('Q', new int[]{1, 0, 1, 0, 1, 0, 1, 1, 0, 0, 1, 1});
        sequence.put('R', new int[]{1, 1, 0, 1, 0, 1, 0, 1, 1, 0, 0, 1});
        sequence.put('S', new int[]{1, 0, 1, 1, 0, 1, 0, 1, 1, 0, 0, 1});
        sequence.put('T', new int[]{1, 0, 1, 0, 1, 1, 0, 1, 1, 0, 0, 1});
        sequence.put('U', new int[]{1, 1, 0, 0, 1, 0, 1, 0, 1, 0, 1, 1});
        sequence.put('V', new int[]{1, 0, 0, 1, 1, 0, 1, 0, 1, 0, 1, 1});
        sequence.put('W', new int[]{1, 1, 0, 0, 1, 1, 0, 1, 0, 1, 0, 1});
        sequence.put('X', new int[]{1, 0, 0, 1, 0, 1, 1, 0, 1, 0, 1, 1});
        sequence.put('Y', new int[]{1, 1, 0, 0, 1, 0, 1, 1, 0, 1, 0, 1});
        sequence.put('Z', new int[]{1, 0, 0, 1, 1, 0, 1, 1, 0, 1, 0, 1});
        sequence.put('-', new int[]{1, 0, 0, 1, 0, 1, 0, 1, 1, 0, 1, 1});
        sequence.put('.', new int[]{1, 1, 0, 0, 1, 0, 1, 0, 1, 1, 0, 1});
        sequence.put(' ', new int[]{1, 0, 0, 1, 1, 0, 1, 0, 1, 1, 0, 1});
        sequence.put('$', new int[]{1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 1});
        sequence.put('/', new int[]{1, 0, 0, 1, 0, 0, 1, 0, 1, 0, 0, 1});
        sequence.put('+', new int[]{1, 0, 0, 1, 0, 1, 0, 0, 1, 0, 0, 1});
        sequence.put('%', new int[]{1, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 1});
        sequence.put('*', new int[]{1, 0, 0, 1, 0, 1, 1, 0, 1, 1, 0, 1});

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
        return 0;
    }
}
