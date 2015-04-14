/**
 * Create by Cequan Zhen 04/12/15
 * 
 */
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.select.Elements;
import org.jsoup.nodes.Element;
import java.sql.*;
import java.io.IOException;
import java.util.ArrayList;


public class Phet {
	static java.sql.Connection conn=null;
	private java.util.ArrayList<String> urls;
	
	String description="";
	String category="";
	String grades="";
	String content="";
	String author="";
	
	public Phet(){
		urls=new ArrayList<String>();
	}
	
	/**
	 * @param args
	 * @throws IOException 
	 * @throws ClassNotFoundException 
	 * @throws IllegalAccessException 
	 * @throws InstantiationException 
	 * @throws SQLException 
	 */
	public static void main(String[] args) throws IOException, InstantiationException, IllegalAccessException, ClassNotFoundException, SQLException {
		Phet phet=new Phet();
		
		if(Phet.ConnectToMySql()){
			phet.urls.add("https://phet.colorado.edu/en/simulations");
			phet.getUrls();
			phet.getDetails();
		}
		
		Phet.disconnectToMySql();
	}
	
	/**
	 * Get the title, Url, imagUrl 
	 */
	private void getUrls(){
		print("run ...");
		int count=0;
		while(!urls.isEmpty()){
			String url=urls.remove(0);
			print(url);
			Document doc=null;
			try{
				doc = Jsoup.connect(url).get();
			}
			catch(Exception ex){
				ex.printStackTrace();
			}
	
			Elements ele = doc.select("td[class=simulation-list-item]");
			for(int i=0; i<ele.size(); i++){
				Element link=ele.get(i).select("a").first();
				//print(link.toString());
				String crsUrl = link.attr("abs:href");
				Element image = link.select("img").first();
				String crsImg = image.absUrl("src");
				Element title = link.select("span").first();
				String crsTitle = title.text();
				//print(crsUrl+'\n'+crsImg+"\n"+crsTitle);
				count++;
				//insert or update into the database
				PreparedStatement stmt=null;
				String sql="select * from education where lesson_link=?";
				PreparedStatement stmtUpdate=null;
				String sqlUpdate="update education set title=?, lesson_image=?, time_scraped= now() "+
						"where lesson_link=?";
				PreparedStatement stmtInsert=null;
				String sqlInsert="insert into education(title, description, lesson_link, lesson_image, category, "+
					"student_grades, author, content_type, time_scraped) values(?,'',?,?,'','','','',now())";
				try{
					stmt=conn.prepareStatement(sql);
					stmt.setString(1, crsUrl);
					ResultSet rs=stmt.executeQuery();
					if(rs.next()){
						//update
						stmtUpdate=conn.prepareStatement(sqlUpdate);
						stmtUpdate.setString(1, crsTitle);
						stmtUpdate.setString(2, crsImg);
						stmtUpdate.setString(3, crsUrl);
						stmtUpdate.execute();
					}
					else{
						//insert
						stmtInsert=conn.prepareStatement(sqlInsert);
						stmtInsert.setString(1, crsTitle);
						stmtInsert.setString(2, crsUrl);
						stmtInsert.setString(3, crsImg);
						stmtInsert.execute();
					}
					
					rs.close();
					
				}
				catch(Exception ex){
					ex.printStackTrace();
				}
				
				
				
			}
			//print(ele.toString());
		}
		print(""+count);
	}
	
	private void getDetails(){
		PreparedStatement stmt=null;
		int count=0;
		String sql="select * from education";
		try{
			stmt=conn.prepareStatement(sql);
			ResultSet rs=stmt.executeQuery();
			while(rs.next()){
				Document doc=null;
				try{
					doc = Jsoup.connect(rs.getString("lesson_link")).get();
					//reset variables
					category="";
					grades="";
					content="";
					description="";
					author="";
					
					//get description
					Element ele = doc.select("span[itemprop=description about]").first();
					description=ele.text();
					
					//get category, grades, and some content
					Elements eles = doc.select("span[class=nml-link-label selected]");
					//print(eles.toString());
					getCategory(eles);
					ele = doc.select("div[class=simulation-main-image-panel]").first();
					getContent(ele);
					
					//get authors
					ele = doc.select("table[class=credits-table]").first();
					ele = ele.select("ul").first();
					getAuthors(ele);
					
					//print(category+"  "+grades+"  "+content+"  "+author);
					
					//update database
					PreparedStatement stmtUpdate=null;
					String sqlUpdate="update education set description=?, category=?, "+
							"student_grades=?, author=?, content_type=?, time_scraped= now() "+
							"where lesson_link=?";
					try{
						//update
						stmtUpdate=conn.prepareStatement(sqlUpdate);
						stmtUpdate.setString(1, description);
						stmtUpdate.setString(2, category);
						stmtUpdate.setString(3, grades);
						stmtUpdate.setString(4, author);
						stmtUpdate.setString(5, content);
						stmtUpdate.setString(6, rs.getString("lesson_link"));
						
						stmtUpdate.execute();
					}
					catch(Exception ex){
						ex.printStackTrace();
					}
					
					count++;
					
				}
				catch(Exception ex){
					ex.printStackTrace();
				}
		
				
			}
			rs.close();
		}
		catch(Exception ex){
			ex.printStackTrace();
		}
		print(""+count);
		
	}
	
	private void getAuthors(Element ele){
		Elements authors=ele.select("li");
		for(int i=0; i<authors.size(); i++){
			String name=authors.get(i).text();
			if(author.length()>0)
				author+=",";
			author+=name;
		}
	}
	
	
	private void getContent(Element ele){
		String [] type={"a[class=sim-run-now sim-button]",
				"a[class=sim-download sim-button]",
				"a[class=sim-embed sim-button]",
				"a[class=simStyleOrange sim-button]"};
		for(int i=0; i<type.length; i++){
			Element ele1 = ele.select(type[i]).first();
			if(ele1!=null){
				if(content.length()>0)
					content+=",";
				content+=ele1.text();
			}
		}
	}
	
	private void getCategory(Elements eles){
		for(int i=0; i<eles.size(); i++){
			Element ele=eles.get(i);
			if(ele.text().equalsIgnoreCase("Motion")){
				if(category.length()>0)
					category+=",";
				category+="Physics-Motion";
			}
			else if(ele.text().equalsIgnoreCase("Sound & Waves")){
				if(category.length()>0)
					category+=",";
				category+="Physics-Sound & Waves";
			}	
			else if(ele.text().equalsIgnoreCase("Work, Energy & Power")){
				if(category.length()>0)
					category+=",";
				category+="Physics-Work, Energy & Power";
			}
			else if(ele.text().equalsIgnoreCase("Heat & Thermo")){
				if(category.length()>0)
					category+=",";
				category+="Physics-Heat & Thermo";
			}
			else if(ele.text().equalsIgnoreCase("Quantum Phenomena")){
				if(category.length()>0)
					category+=",";
				category+="Physics-Quantum Phenomena";
			}
			else if(ele.text().equalsIgnoreCase("Light & Radiation")){
				if(category.length()>0)
					category+=",";
				category+="Physics-Light & Radiation";
			}
			else if(ele.text().equalsIgnoreCase("Electricity, Magnets & Circuits")){
				if(category.length()>0)
					category+=",";
				category+="Physics-Electricity, Magnets & Circuits";
			}
			else if(ele.text().equalsIgnoreCase("Biology")){
				if(category.length()>0)
					category+=",";
				category+="Biology";
			}
			else if(ele.text().equalsIgnoreCase("General Chemistry")){
				if(category.length()>0)
					category+=",";
				category+="Chemistry-General Chemistry";
			}
			else if(ele.text().equalsIgnoreCase("Quantum Chemistry")){
				if(category.length()>0)
					category+=",";
				category+="Chemistry-Quantum Chemistry";
			}
			else if(ele.text().equalsIgnoreCase("Earth Science")){
				if(category.length()>0)
					category+=",";
				category+="Earth Science";
			}
			else if(ele.text().equalsIgnoreCase("Math")){
				if(category.length()>0)
					category+=",";
				category+="Math";
			}
			else if(ele.text().equalsIgnoreCase("Elementary School")){
				if(grades.length()>0)
					grades+=",";
				grades+="k,1,2,3,4,5";
			}
			else if(ele.text().equalsIgnoreCase("Middle School")){
				if(grades.length()>0)
					grades+=",";
				grades+="6,7,8";
			}
			else if(ele.text().equalsIgnoreCase("High School")){
				if(grades.length()>0)
					grades+=",";
				grades+="9,10,11,12";
			}
			else if(ele.text().equalsIgnoreCase("University")){
				if(grades.length()>0)
					grades+="";
				grades+="";
			}
			else if(ele.text().equalsIgnoreCase("iPad/Tablet")){
				if(content.length()>0)
					content+=",";
				content+="iPad/Tablet";
			}
			else if(ele.text().equalsIgnoreCase("Chromebook")){
				if(content.length()>0)
					content+=",";
				content+="Chromebook";
			}
			else{
				print("xxx->"+ele.text());
			}
				
		
		}
	}
	
	
	private String getGrades(String school){
		if(school.equalsIgnoreCase("Elementary School"))
			return "k,1,2,3,4,5";
		else if(school.equalsIgnoreCase("Middle School"))
			return "6,7,8";
		else if(school.equalsIgnoreCase("High School"))
			return "9,10,11,12";
		else if(school.equalsIgnoreCase("University"))
			return "";
		else
			return "";
		
	}
	
	static boolean ConnectToMySql(){
		boolean result=false;
		try{
			
			Class.forName("com.mysql.jdbc.Driver").newInstance();
			conn = DriverManager.getConnection("jdbc:mysql://localhost/scrapedcourse","root","");
			result=true;
		}
		catch(Exception ex){
			print("Cannot connect to Database!");
			ex.printStackTrace();
			conn=null;
		}
		return result;
	}
	
	static void disconnectToMySql(){
		if(conn!=null){
			try{
				conn.close();
				conn=null;
			}
			catch(Exception ex){
				conn=null;
				ex.printStackTrace();
			}
		}
	}
	
	private static void print(String str){
		System.out.println(str);
	}
	
	

}
