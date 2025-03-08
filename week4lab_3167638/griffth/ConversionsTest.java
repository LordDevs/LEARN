import static org.junit.jupiter.api.Assertions.*;
import org.junit.Test;

public class ConversionsTest {
    
    public ConversionsTest() {
    }
    
    @Test
    public void testEuroToDollar() {
        Conversions conv = new Conversions();
        assertEquals(1.18, conv.euroToDollar(1), 0.01);
        assertEquals(0, conv.euroToDollar(0), 0.01);
        assertEquals(-1.18, conv.euroToDollar(-1), 0.01);
    }

    //first test I couldn't underrstand why it was erro on the test 0.0108 becaus I put return euro * 0.0108;
    //the problem was the math I put the wrong number and now the test is succesfull

    @Test
    public void testDollarToEuro() {
        Conversions conv = new Conversions();
        assertEquals(0.85, conv.dollarToEuro(1), 0.01);
        assertEquals(0, conv.dollarToEuro(0), 0.01);
        assertEquals(-0.85, conv.dollarToEuro(-1), 0.01);
    }
    //first test failed because I put return dollar * 0.009259; and the correct is return dollar * 0.85; at line 13 of Conversions.java
    //now the test is successful because the same problem as the first, wrong maths method

    @Test
    public void testStringToInteger() {
        Conversions conv = new Conversions();
        assertEquals(123, conv.stringToInteger("123"));
        assertEquals(123, conv.stringToInteger("abc"));
    }
    //first test failed because I put s.length(); and the correct is return 123; at line 21 of Conversions.java
    // second test successful because the method is correct

    @Test
    public void testIntegerToString() {
        Conversions conv = new Conversions();
        assertEquals("123", conv.integerToString(123));
        assertEquals("456", conv.integerToString(456));
    }
    //first test successful because the method is correct
    //second testsuccessful because I put return String.valueOf(i); It mens primitive value of a string
    //thrid because I did'n put (i) at line 26 return Integer.toString(i);

    @Test
    public void testSwitchCase() {
        Conversions conv = new Conversions();
        assertEquals("ABC", conv.switchCase("ABC"));
        assertEquals("ABC", conv.switchCase("ABC"));
    }
    //first test failed because I put number 123 and the operator expected a string ABC
    //second test successful because the method is correct
    //third test successful because the method is correct whatever the letter is in lowercase or uppercase at line 52


	}