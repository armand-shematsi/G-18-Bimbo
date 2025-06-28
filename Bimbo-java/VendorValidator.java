import java.sql.*;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;
import java.util.regex.Pattern;

public class VendorValidator {
    private static final Pattern EMAIL_PATTERN = Pattern.compile("^[A-Za-z0-9+_.-]+@[A-Za-z0-9.-]+$");
    private static final Pattern PHONE_PATTERN = Pattern.compile("^\\+?[1-9]\\d{1,14}$");
    private static final Pattern ZIP_CODE_PATTERN = Pattern.compile("^\\d{5}(-\\d{4})?$");
    private static final Pattern TAX_ID_PATTERN = Pattern.compile("^\\d{2}-\\d{7}$");

    public static void main(String[] args) {
        // Schedule the validation task to run every 5 minutes
        ScheduledExecutorService scheduler = Executors.newScheduledThreadPool(1);
        scheduler.scheduleAtFixedRate(VendorValidator::runValidation, 0, 1, TimeUnit.MINUTES);

        // Keep the main thread alive to allow scheduled tasks to run
        try {
            Thread.sleep(Long.MAX_VALUE);
        } catch (InterruptedException e) {
            Thread.currentThread().interrupt();
        }
    }

    private static void runValidation() {
        try (Connection conn = DatabaseUtil.getConnection();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery("SELECT * FROM vendors WHERE status = 'pending' LIMIT 100")) {

            while (rs.next()) {
                Vendor vendor = new Vendor();
                vendor.setId(rs.getLong("id"));
                vendor.setName(rs.getString("name"));
                vendor.setEmail(rs.getString("email"));
                vendor.setPhone(rs.getString("phone"));
                vendor.setAddress(rs.getString("address"));
                vendor.setCity(rs.getString("city"));
                vendor.setState(rs.getString("state"));
                vendor.setZipCode(rs.getString("zip_code"));
                vendor.setBusinessType(rs.getString("business_type"));
                vendor.setTaxId(rs.getString("tax_id"));
                vendor.setBusinessLicense(rs.getString("business_license"));
                vendor.setStatus(rs.getString("status"));
                vendor.setCreatedAt(rs.getTimestamp("created_at").toLocalDateTime());
                vendor.setUpdatedAt(rs.getTimestamp("updated_at").toLocalDateTime());
                vendor.setSales(rs.getDouble("sales"));
                vendor.setAnnualRevenue(rs.getDouble("annual_revenue"));
                vendor.setYearsInBusiness(rs.getInt("years_in_business"));
                vendor.setRegulatoryCertification(rs.getString("regulatory_certification"));

                // Business validation logic
                boolean passes = vendor.getAnnualRevenue() >= 100000
                        && vendor.getYearsInBusiness() >= 2
                        && vendor.getRegulatoryCertification() != null
                        && !vendor.getRegulatoryCertification().trim().isEmpty();

                // Retailer restriction: must have annual_revenue >= 50000 and years_in_business >= 1
                boolean canBeRetailer = vendor.getAnnualRevenue() >= 50000 && vendor.getYearsInBusiness() >= 1;

                if (passes) {
                    updateVendorStatus(conn, vendor.getId(), "visit_scheduled");
                    // Send email notification (placeholder)
                    sendEmailNotification(vendor.getEmail(), "Your vendor application has met the criteria and a facility visit will be scheduled.");
                } else if (canBeRetailer) {
                    updateVendorStatus(conn, vendor.getId(), "pending");
                    // Optionally notify about pending status
                } else {
                    updateVendorStatus(conn, vendor.getId(), "rejected");
                    // Send rejection email (placeholder)
                    sendEmailNotification(vendor.getEmail(), "Your vendor application has been rejected. Please review the requirements and reapply.");
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    private static ValidationResult validateVendor(Vendor vendor) {
        StringBuilder errors = new StringBuilder();

        // Validate name: must not be null or empty
        if (vendor.getName() == null || vendor.getName().trim().isEmpty()) {
            errors.append("Name is required. ");
        }

        // Validate email: must match standard email pattern
        if (vendor.getEmail() == null || !EMAIL_PATTERN.matcher(vendor.getEmail()).matches()) {
            errors.append("Invalid email format. ");
        }

        // Validate phone: must match E.164 international phone number format
        if (vendor.getPhone() == null || !PHONE_PATTERN.matcher(vendor.getPhone()).matches()) {
            errors.append("Invalid phone number format. ");
        }

        // Validate address: must not be null or empty
        if (vendor.getAddress() == null || vendor.getAddress().trim().isEmpty()) {
            errors.append("Address is required. ");
        }

        // Validate city: must not be null or empty
        if (vendor.getCity() == null || vendor.getCity().trim().isEmpty()) {
            errors.append("City is required. ");
        }

        // Validate state: must not be null or empty
        if (vendor.getState() == null || vendor.getState().trim().isEmpty()) {
            errors.append("State is required. ");
        }

        // Validate zip code: must match US ZIP code format (5 digits or 5+4 digits)
        if (vendor.getZipCode() == null || !ZIP_CODE_PATTERN.matcher(vendor.getZipCode()).matches()) {
            errors.append("Invalid zip code format. ");
        }

        // Validate business type: must not be null or empty
        if (vendor.getBusinessType() == null || vendor.getBusinessType().trim().isEmpty()) {
            errors.append("Business type is required. ");
        }

        // Validate tax ID: must match format XX-XXXXXXX
        if (vendor.getTaxId() == null || !TAX_ID_PATTERN.matcher(vendor.getTaxId()).matches()) {
            errors.append("Invalid tax ID format (should be XX-XXXXXXX). ");
        }

        // Validate business license: must not be null or empty
        if (vendor.getBusinessLicense() == null || vendor.getBusinessLicense().trim().isEmpty()) {
            errors.append("Business license is required. ");
        }

        // Validate sales: must be at least 10,000
        if (vendor.getSales() < 10000) {
            errors.append("Sales must be at least $10,000. ");
        }

        String errorMessage = errors.toString().trim();
        return new ValidationResult(errorMessage.isEmpty(), errorMessage.isEmpty() ? "All validations passed" : errorMessage);
    }

    private static void updateVendorStatus(Connection conn, Long vendorId, String status) {
        try (PreparedStatement pstmt = conn.prepareStatement(
                "UPDATE vendors SET status = ?, updated_at = NOW() WHERE id = ?")) {
            pstmt.setString(1, status);
            pstmt.setLong(2, vendorId);
            pstmt.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    // Update updateUserRoleByEmail to accept role
    private static void updateUserRoleByEmail(Connection conn, String email, String role) {
        try (PreparedStatement pstmt = conn.prepareStatement(
                "UPDATE users SET role = ? WHERE email = ?")) {
            pstmt.setString(1, role);
            pstmt.setString(2, email);
            pstmt.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    // Placeholder for sending email notifications
    private static void sendEmailNotification(String to, String message) {
        // Integrate with an email service or SMTP server here
        System.out.println("Email to " + to + ": " + message);
    }

    private static class ValidationResult {
        private final boolean valid;
        private final String message;

        public ValidationResult(boolean valid, String message) {
            this.valid = valid;
            this.message = message;
        }

        public boolean isValid() {
            return valid;
        }

        public String getMessage() {
            return message;
        }
    }
}