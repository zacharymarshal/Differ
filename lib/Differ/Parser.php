<?php

namespace Differ;

class Parser
{
	private $file;

	public function __construct($file) {
		$this->file = $file;
	}

	public function parse() {
		$lines = file($this->file);
		if ( ! $lines) {
			return false;
		}
		$files = array();

		while (count($lines)) {
			$line = trim(array_shift($lines));

			if (preg_match('/^diff/', $line)) {
				list($junk_diff, $junk_git, $filename, $junk_file_b) = explode(' ', $line);
				$junk_line = array_shift($lines);
				$action = 'modified';
			}
			else if (preg_match('/^===/', $line)) {
				list($junk, $action, $morejunk, $filename) = explode(' ', $line);
			}
			else {
				// not valid diff
				continue;
			}


			$files[$filename] = array(
				"type" => $action,
				"filename" => $filename,
				"lines" => array()
			);

			$diff_lines = array();

			$line_num = 0;

			while (count($lines)) {
				$line_num++;
				$line = array_shift($lines);

				if (preg_match('/^\+\+\+/', $line)) {
					$diff_lines[$line_num] = array(
						"type" => "info",
						"line" => $line
					);

					continue;
				}

				if (preg_match('/^---/', $line)) {
					$diff_lines[$line_num] = array(
						"type" => "info",
						"line" => $line
					);

					continue;
				}

				if (preg_match('/^@@/', $line)) {
					$diff_lines[$line_num] = array(
						"type" => "info",
						"line" => $line
					);

					continue;
				}

				if (preg_match('/^ /', $line)) {
					$diff_lines[$line_num] = array(
						"type" => "context",
						"line" => $line
					);
					
					continue;
				}

				if (preg_match('/^\+/', $line)) {
					$diff_lines[$line_num] = array(
						"type" => "new",
						"line" => $line
					);

					continue;
				}

				if (preg_match('/^-/', $line)) {
					$diff_lines[$line_num] = array(
						"type" => "removed",
						"line" => $line
					);
					
					continue;
				}

				if (preg_match('/^\n/', $line)) {
					break;// end of file
				}
			}

			$files[$filename]['lines'] = $diff_lines;
		}

		return $files;
	}
}
