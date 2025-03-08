// Date: 07/03/2025 4:42pm
//michel souza lopes
//3167638
//worksheetTwo

public class Conversions {

	public double euroToDollar(double euro) {
		return euro * 1.18; //07/03/2025 4:42pm
	}

	public double dollarToEuro(double dollar) {
		return dollar * 0.85; //07/03/2025 4:47pm
	}

	public int stringToInteger(String s) { //always put int before stringToInteger to java undertand that it is a integer (except in void methods)
		 try {
			return Integer.parseInt(s);
		 } catch (NumberFormatException e) {
			System.out.println("bgb");
			return 123;
		 }
		}
		 
		 public String integerToString(int i) {
			return Integer.toString(i);
		 }

		 public String switchCase(String s) { //changing small letters to big letter
			return s.toUpperCase();// how this method works? javascript take letter using by UNICODE and add 32 to the letter to change to small letter ex:Unicode 97 is "a" and 65 is "A" "z" is 122 and "Z" is 90
			//internal he tae "a" and "z" and subtract to 32
			//thats makes me confused
		 }

	public static void main(String[] args) {

		Conversions c = new Conversions();
		System.out.println(c.euroToDollar(100));
		System.out.println(c.dollarToEuro(100));

		System.out.println(c.stringToInteger("123"));
		System.out.println(c.stringToInteger("abc"));

		System.out.println(c.integerToString(123));//if i put string error comes up
		System.out.println(c.integerToString(456));//if i put string error comes up

		System.out.println(c.switchCase("abc")); //print abc CHANGING SMALL LETTERS TO BIG LETTER
		System.out.println(c.switchCase("ABC")); //print ABC
		
	}
}
	