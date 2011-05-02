import java.util.ArrayList;
import java.util.List;
public class Test {
  public static void main(String[] args) {
    Order order = new Order();
    Product product = new Product();
    product.setName("INNOVA");
    product.setPrice(9786);
    order.addOrderLine(product);
    order = null; // OrderLines are destroyed along with order
    //Because orderLine and Order have strong relationships i.e. composition.
    //But Product will exist, even orderLine is deleted because it have weaker relationship
    //i.e. Composition.
  }
}


class OrderLine {
  Product product;
  int quantity;
  // Add many attribute per you order line.
  public Product getProduct() {
    return this.product;
  }
  public void setProduct(Product product) {
    this.product = product;
  }
}

class Product {
  private String name;
  private int price;
  // Add many attributes as you need.
  public String getName() {
    return name;
  }
  public void setName(String name) {
    this.name = name;
  }
  public int getPrice() {
    return price;
  }
  public void setPrice(int price) {
    this.price = price;
  }
}

class Order {
  private List lines = new ArrayList();
    String customerName;
    // Add many order attributes as you need.
    public boolean addOrderLine(Product product) {
    OrderLine line = new OrderLine();
    line.setProduct(product);
    lines.add(line);
    return true;
  }
}