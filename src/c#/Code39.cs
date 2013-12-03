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
using System;
using System.Collections.Generic;

namespace OpenBarcode
{
	public class Code39 : LinearBarcode
	{
		private string code;
		private int[] bars;

		public Code39(string code) {
			this.code = code.ToUpper().Trim();

			bars = new int[(code.Length + 2) * 12];

			BuildSequence();
		}

		public void BuildSequence() {
			string encoded = "*" + code + "*";

			int p = 0;
			for (int i = 0; i < encoded.Length; i++) {
				int[] sequence = MapSequence(encoded[i], i);
				Array.Copy(sequence, 0, bars, p, sequence.Length);
				p += sequence.Length;
			}
		}

		public int[] MapSequence(char c, int pos) {
			Dictionary<char, int[]> sequence = new Dictionary<char, int[]>();

			sequence.Add('0', new int[]{1, 0, 1, 0, 0, 1, 1, 0, 1, 1, 0, 1});
			sequence.Add('1', new int[]{1, 1, 0, 1, 0, 0, 1, 0, 1, 0, 1, 1});
			sequence.Add('2', new int[]{1, 0, 1, 1, 0, 0, 1, 0, 1, 0, 1, 1});
			sequence.Add('3', new int[]{1, 1, 0, 1, 1, 0, 0, 1, 0, 1, 0, 1});
			sequence.Add('4', new int[]{1, 0, 1, 0, 0, 1, 1, 0, 1, 0, 1, 1});
			sequence.Add('5', new int[]{1, 1, 0, 1, 0, 0, 1, 1, 0, 1, 0, 1});
			sequence.Add('6', new int[]{1, 0, 1, 1, 0, 0, 1, 1, 0, 1, 0, 1});
			sequence.Add('7', new int[]{1, 0, 1, 0, 0, 1, 0, 1, 1, 0, 1, 1});
			sequence.Add('8', new int[]{1, 1, 0, 1, 0, 0, 1, 0, 1, 1, 0, 1});
			sequence.Add('9', new int[]{1, 0, 1, 1, 0, 0, 1, 0, 1, 1, 0, 1});
			sequence.Add('A', new int[]{1, 1, 0, 1, 0, 1, 0, 0, 1, 0, 1, 1});
			sequence.Add('B', new int[]{1, 0, 1, 1, 0, 1, 0, 0, 1, 0, 1, 1});
			sequence.Add('C', new int[]{1, 1, 0, 1, 1, 0, 1, 0, 0, 1, 0, 1});
			sequence.Add('D', new int[]{1, 0, 1, 0, 1, 1, 0, 0, 1, 0, 1, 1});
			sequence.Add('E', new int[]{1, 1, 0, 1, 0, 1, 1, 0, 0, 1, 0, 1});
			sequence.Add('F', new int[]{1, 0, 1, 1, 0, 1, 1, 0, 0, 1, 0, 1});
			sequence.Add('G', new int[]{1, 0, 1, 0, 1, 0, 0, 1, 1, 0, 1, 1});
			sequence.Add('H', new int[]{1, 1, 0, 1, 0, 1, 0, 0, 1, 1, 0, 1});
			sequence.Add('I', new int[]{1, 0, 1, 1, 0, 1, 0, 0, 1, 1, 0, 1});
			sequence.Add('J', new int[]{1, 0, 1, 0, 1, 1, 0, 0, 1, 1, 0, 1});
			sequence.Add('K', new int[]{1, 1, 0, 1, 0, 1, 0, 1, 0, 0, 1, 1});
			sequence.Add('L', new int[]{1, 0, 1, 1, 0, 1, 0, 1, 0, 0, 1, 1});
			sequence.Add('M', new int[]{1, 1, 0, 1, 1, 0, 1, 0, 1, 0, 0, 1});
			sequence.Add('N', new int[]{1, 0, 1, 0, 1, 1, 0, 1, 0, 0, 1, 1});
			sequence.Add('O', new int[]{1, 1, 0, 1, 0, 1, 1, 0, 1, 0, 0, 1});
			sequence.Add('P', new int[]{1, 0, 1, 1, 0, 1, 1, 0, 1, 0, 0, 1});
			sequence.Add('Q', new int[]{1, 0, 1, 0, 1, 0, 1, 1, 0, 0, 1, 1});
			sequence.Add('R', new int[]{1, 1, 0, 1, 0, 1, 0, 1, 1, 0, 0, 1});
			sequence.Add('S', new int[]{1, 0, 1, 1, 0, 1, 0, 1, 1, 0, 0, 1});
			sequence.Add('T', new int[]{1, 0, 1, 0, 1, 1, 0, 1, 1, 0, 0, 1});
			sequence.Add('U', new int[]{1, 1, 0, 0, 1, 0, 1, 0, 1, 0, 1, 1});
			sequence.Add('V', new int[]{1, 0, 0, 1, 1, 0, 1, 0, 1, 0, 1, 1});
			sequence.Add('W', new int[]{1, 1, 0, 0, 1, 1, 0, 1, 0, 1, 0, 1});
			sequence.Add('X', new int[]{1, 0, 0, 1, 0, 1, 1, 0, 1, 0, 1, 1});
			sequence.Add('Y', new int[]{1, 1, 0, 0, 1, 0, 1, 1, 0, 1, 0, 1});
			sequence.Add('Z', new int[]{1, 0, 0, 1, 1, 0, 1, 1, 0, 1, 0, 1});
			sequence.Add('-', new int[]{1, 0, 0, 1, 0, 1, 0, 1, 1, 0, 1, 1});
			sequence.Add('.', new int[]{1, 1, 0, 0, 1, 0, 1, 0, 1, 1, 0, 1});
			sequence.Add(' ', new int[]{1, 0, 0, 1, 1, 0, 1, 0, 1, 1, 0, 1});
			sequence.Add('$', new int[]{1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 1});
			sequence.Add('/', new int[]{1, 0, 0, 1, 0, 0, 1, 0, 1, 0, 0, 1});
			sequence.Add('+', new int[]{1, 0, 0, 1, 0, 1, 0, 0, 1, 0, 0, 1});
			sequence.Add('%', new int[]{1, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 1});
			sequence.Add('*', new int[]{1, 0, 0, 1, 0, 1, 1, 0, 1, 1, 0, 1});

			return sequence[c];
		}

		public int[] Barcode() {
			return bars;
		}

		public string Code() {
			return code;
		}

		public int CalculateCheckDigit() {
			return 0;
		}
	}
}

