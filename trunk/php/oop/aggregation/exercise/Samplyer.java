// ShambhuDubey: Aggregation Example
import java.util.ArrayList;
public class Aggregation {
  public static void main(String[] args){
    MusicPlayer player = new MusicPlayer();
    Car car = new Car();
    car.addPlayer(player);
  }
}


/**
* Client, Aggregate, Whole
*/
class Car {
  private ArrayList players;
  public Car() {
    // players list is empty, we can attach players via addPlayer(), so
    // that car CONTAINS players, however our car object is not dependant
    // on player for initialization – we can initialize car objects even
    // if we have no intention to put player into them
    players = new ArrayList();
  }

  public void addPlayer(MusicPlayer p)
  {
    players.add(p);
  }
}


/**
* Dump MusicPlayer class
*/
class MusicPlayer {



}