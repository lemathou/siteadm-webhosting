require ["relational","comparator-i;ascii-numeric","fileinto"];
# rule:[Spam vers poubelle]
if header :value "gt" :comparator "i;ascii-numeric" "X-Spam-Score" "12"
{
	fileinto "Trash";
	stop;
}
# rule:[Spam vers indésirables]
if header :value "gt" :comparator "i;ascii-numeric" "X-Spam-Score" "6.31"
{
	fileinto "Junk";
	stop;
}
