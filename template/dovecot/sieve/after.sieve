require ["relational","comparator-i;ascii-numeric","fileinto"];
# rule:[Spam vers poubelle]
if header :value "gt" :comparator "i;ascii-numeric" "X-Spam-Score" "8"
{
	fileinto "Trash";
	stop;
}
# rule:[Spam vers indésirables]
if header :value "gt" :comparator "i;ascii-numeric" "X-Spam-Score" "4"
{
	fileinto "Junk";
	stop;
}
