# nessus_parser
a simple nessus parser based on php to extract certain filed to be populated in a table.

it scan for all .nessus in a folder, read it and output into csv.
only grab the following:
-plugin_name
-port and protocol
-synopsis
-solution
-risk_factor (does not include value of none)
