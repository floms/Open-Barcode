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
	public class EAN : LinearBarcode
	{
		private string code;
		private int baseDigit;
		private int[] bars;

		public EAN(string code) {
			if (code.Length != 12 && code.Length != 13) {
				throw new Exception("Invalid EAN13 code length");
			}

			this.code = code.Substring(0, 12);

			this.baseDigit = Int32.Parse(code[0].ToString());

			bars = new int[95];
			BuildSequence();
		}

		protected Dictionary<char, int[]> codeL() {
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

			return sequence;
		}

		protected Dictionary<char, int[]> codeG() {
			Dictionary<char, int[]> sequence = codeL();

			sequence.Add('0', new int[]{0, 1, 0, 0, 1, 1, 1});
			sequence.Add('1', new int[]{0, 1, 1, 0, 0, 1, 1});
			sequence.Add('2', new int[]{0, 0, 1, 1, 0, 1, 1});
			sequence.Add('3', new int[]{0, 1, 0, 0, 0, 0, 1});
			sequence.Add('4', new int[]{0, 0, 1, 1, 1, 0, 1});
			sequence.Add('5', new int[]{0, 1, 1, 1, 0, 0, 1});
			sequence.Add('6', new int[]{0, 0, 0, 0, 1, 0, 1});
			sequence.Add('7', new int[]{0, 0, 1, 0, 0, 0, 1});
			sequence.Add('8', new int[]{0, 0, 0, 1, 0, 0, 1});
			sequence.Add('9', new int[]{0, 0, 1, 0, 1, 1, 1});

			return sequence;
		}

		protected Dictionary<char, int[]> codeR() {
			Dictionary<char, int[]> sequence = codeL();

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

			return sequence;
		}

		public int[] MapSequence(char c, int pos) {
			int b = this.baseDigit;

			Dictionary<char, int[]> sequence;

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

			return sequence[c];
		}

		public void BuildSequence() {
			code += CalculateCheckDigit();

			string encoded = "*" + code.Substring(1, 7) + "#" + code.Substring(7, 13) + "*";

			int p = 0;
			for (int i = 0; i < encoded.Length; i++) {
				int[] sequence = MapSequence(encoded[i], i);
				Array.Copy(sequence, 0, bars, p, sequence.Length);
				p += sequence.Length;
			}
		}

		public int[] Barcode() {
			return bars;
		}

		public string Code() {
			return code;
		}

		public int CalculateCheckDigit() {
			int checksum = 0;

			for (int i = 0; i < 12; i++) {
				int digit = Int32.Parse(code[i].ToString());

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
}

