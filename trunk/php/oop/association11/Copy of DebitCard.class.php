

public class AntiAirCraftGun {
 
   private Bomber target;
   private int positionX;
   private int positionY;
   private int damage;
 
   public void setTarget(Bomber newTarget)
   {
     this.target = newTarget;
   }
 
   //rest of AntiAircraftGun class
 }
 
 public class Bomber {
 
   private AntiAirCraftGun target;
   private int positionX;
   private int positionY;
   private int damage;
 
   public void setTarget(AntiAirCraftGun newTarget)
   {
     this.target = newTarget;
   }
 
   //rest of Bomber class
 } 