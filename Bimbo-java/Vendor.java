import java.time.LocalDateTime;

public class Vendor {
    private Long id;
    private String name;
    private String email;
    private String phone;
    private String address;
    private String city;
    private String state;
    private String zipCode;
    private String businessType;
    private String taxId;
    private String businessLicense;
    private String status;
    private LocalDateTime createdAt;
    private LocalDateTime updatedAt;
    private double sales;
    private double annualRevenue;
    private int yearsInBusiness;
    private String regulatoryCertification;

    public Vendor() {
    }

    public Vendor(Long id, String name, String email, String phone, String address,
                 String city, String state, String zipCode, String businessType,
                 String taxId, String businessLicense, String status,
                 LocalDateTime createdAt, LocalDateTime updatedAt, double sales) {
        this.id = id;
        this.name = name;
        this.email = email;
        this.phone = phone;
        this.address = address;
        this.city = city;
        this.state = state;
        this.zipCode = zipCode;
        this.businessType = businessType;
        this.taxId = taxId;
        this.businessLicense = businessLicense;
        this.status = status;
        this.createdAt = createdAt;
        this.updatedAt = updatedAt;
        this.sales = sales;
    }

    // Getters and Setters
    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }

    public String getName() { return name; }
    public void setName(String name) { this.name = name; }

    public String getEmail() { return email; }
    public void setEmail(String email) { this.email = email; }

    public String getPhone() { return phone; }
    public void setPhone(String phone) { this.phone = phone; }

    public String getAddress() { return address; }
    public void setAddress(String address) { this.address = address; }

    public String getCity() { return city; }
    public void setCity(String city) { this.city = city; }

    public String getState() { return state; }
    public void setState(String state) { this.state = state; }

    public String getZipCode() { return zipCode; }
    public void setZipCode(String zipCode) { this.zipCode = zipCode; }

    public String getBusinessType() { return businessType; }
    public void setBusinessType(String businessType) { this.businessType = businessType; }

    public String getTaxId() { return taxId; }
    public void setTaxId(String taxId) { this.taxId = taxId; }

    public String getBusinessLicense() { return businessLicense; }
    public void setBusinessLicense(String businessLicense) { this.businessLicense = businessLicense; }

    public String getStatus() { return status; }
    public void setStatus(String status) { this.status = status; }

    public LocalDateTime getCreatedAt() { return createdAt; }
    public void setCreatedAt(LocalDateTime createdAt) { this.createdAt = createdAt; }

    public LocalDateTime getUpdatedAt() { return updatedAt; }
    public void setUpdatedAt(LocalDateTime updatedAt) { this.updatedAt = updatedAt; }

    public double getSales() { return sales; }
    public void setSales(double sales) { this.sales = sales; }

    public double getAnnualRevenue() { return annualRevenue; }
    public void setAnnualRevenue(double annualRevenue) { this.annualRevenue = annualRevenue; }

    public int getYearsInBusiness() { return yearsInBusiness; }
    public void setYearsInBusiness(int yearsInBusiness) { this.yearsInBusiness = yearsInBusiness; }

    public String getRegulatoryCertification() { return regulatoryCertification; }
    public void setRegulatoryCertification(String regulatoryCertification) { this.regulatoryCertification = regulatoryCertification; }
} 