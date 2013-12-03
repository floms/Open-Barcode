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
	public class UPC : LinearBarcode
	{
		private int[] bars;
		private string code;

		public UPC(string code) {
			if (code.Length != 11 && code.Length != 12) {
				throw new Exception("Invalid UPC code length");
			}

			this.code = code.Substring(0, 11);

			bars = new int[95];
			BuildSequence();
		}

		public void BuildSequence() {
			code += CalculateCheckDigit();

			string encoded = "*" + code.Substring(0, 6) + "#" + code.Substring(6, 12) + "*";


			int p = 0;
			for (int i = 0; i < encoded.Length; i++) {
				int[] sequence = MapSequence(encoded[i], i);
				Array.Copy(sequence, 0, bars, p, sequence.Length);
				p += sequence.Length;
			}
		}

		public int[] MapSequence(char c, int pos) {
			Dictionary<char, int[]> sequence = new Dictionary<char, int[]>();

			sequence.Add('0', new int[]{0, 0, 0, 1, 1, 0, 1});
			sequence.Add('1', new int[]{0, 0, 1, 1, 0, 0, 1});
			sequence.Add('2', new int[]{0, 0, 1, 0, 0, 1, 1});
			sequence.Add('3', new int[]{0, 1, 1, 1, 1, 0, 1});
			sequence.Add('4', new int[]{0, 1, 0, 0, 0, 1, 1});
			sequence.Add('5', new int[]{0, 1, 1, 0, 0, 0, 1});
			sequence.Add('6', new int[]{0, 1, 0, 1, 1, 1, 1});
			sequence.Add('7', new int[]{0, 1, 1, 1, 0, 1, 1});
			sequence.Add('8', new int[]{0, 1, 1, 0, 1, 1, 1});
			sequence.Add('9', new int[]{0, 0, 0, 1, 0, 1, 1});
			sequence.Add('#', new int[]{0, 1, 0, 1, 0});
			sequence.Add('*', new int[]{1, 0, 1});

			if (pos >= 7) {
				sequence.Add('0', new int[]{1, 1, 1, 0, 0, 1, 0});
				sequence.Add('1', new int[]{1, 1, 0, 0, 1, 1, 0});
				sequence.Add('2', new int[]{1, 1, 0, 1, 1, 0, 0});
				sequence.Add('3', new int[]{1, 0, 0, 0, 0, 1, 0});
				sequence.Add('4', new int[]{1, 0, 1, 1, 1, 0, 0});
				sequence.Add('5', new int[]{1, 0, 0, 1, 1, 1, 0});
				sequence.Add('6', new int[]{1, 0, 1, 0, 0, 0, 0});
				sequence.Add('7', new int[]{1, 0, 0, 0, 1, 0, 0});
				sequence.Add('8', new int[]{1, 0, 0, 1, 0, 0, 0});
				sequence.Add('9', new int[]{1, 1, 1, 0, 1, 0, 0});
			}

			return sequence[c];
		}

		public int[] Barcode() {
			return bars;
		}

		public string Code() {
			return code;
		}

		public int CalculateCheckDigit() {
			int checksum = 0;

			for (int i = 0; i < 11; i++) {
				int digit = Int16.Parse(code[i].ToString());

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
}