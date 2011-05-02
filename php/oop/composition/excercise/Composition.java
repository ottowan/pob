
// ShambhuDubey: Composition Example
import java.util.ArrayList;
public class Composition {
  public static void main(String[] args) {
    // Frame objects life-cycle is totally under container class controll
    Car car = new Car();
  }
}

class Car {
  private ArrayList frames;
  public Car() {
    frames = new ArrayList();
    frames.add(new CarFrame());
  }
}

class CarFrame {

}

