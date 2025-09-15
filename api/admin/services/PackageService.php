<?php
class PackageService {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function createPackage($name, $price, $description, $validMonths, $options, $papers) {
        try {
            $this->db->beginTransaction();
            $sql = "INSERT INTO packages (package_name, package_price, package_description, valid_months)
                    VALUES (:name, :price, :description, :valid_months)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':valid_months', $validMonths);
            $stmt->execute();
            $packageId = $this->db->lastInsertId();
            foreach ($options as $option) {
                $sql = "INSERT INTO package_options (packages_id, package_options)
                        VALUES (:package_id, :option)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':package_id', $packageId);
                $stmt->bindParam(':option', $option);
                $stmt->execute();
            }
            foreach ($papers as $paper) {
                $sql = "INSERT INTO packages_has_papers (packages_id, papers_paper_id)
                        VALUES (:package_id, :paper_id)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':package_id', $packageId);
                $stmt->bindParam(':paper_id', $paper);
                $stmt->execute();
            }
            $this->db->commit();
            return $packageId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception("Error creating package: " . $e->getMessage());
        }
    }
    public function getPackages() {
        $sql = "SELECT * FROM packages";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updatePackage($id, $name, $price, $description, $validMonths, $options, $papers) {
        try {
            $this->db->beginTransaction();
            $sql = "UPDATE packages SET package_name = :name, package_price = :price, package_description = :description, valid_months = :valid_months WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':valid_months', $validMonths);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $this->deletePackageOptions($id);
            foreach ($options as $option) {
                $sql = "INSERT INTO packages_has_options (packages_id, package_options_id)
                        VALUES (:package_id, :option)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':package_id', $id);
                $stmt->bindParam(':option', $option);
                $stmt->execute();
            }
            $this->deletePackagePapers($id);
            foreach ($papers as $paper) {
                $sql = "INSERT INTO packages_has_papers (packages_id, papers_paper_id)
                        VALUES (:package_id, :paper_id)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':package_id', $id);
                $stmt->bindParam(':paper_id', $paper);
                $stmt->execute();
            }
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception("Error updating package: " . $e->getMessage());
        }
    }
    public function deletePackage($id) {
        try {
            $this->db->beginTransaction();
            $this->deletePackageOptions($id);
            $this->deletePackagePapers($id);
            $sql = "DELETE FROM packages WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception("Error deleting package: " . $e->getMessage());
        }
    }
    private function deletePackageOptions($packageId) {
        $sql = "DELETE FROM packages_has_options WHERE packages_id = :package_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':package_id', $packageId);
        $stmt->execute();
    }
    private function deletePackagePapers($packageId) {
        $sql = "DELETE FROM packages_has_papers WHERE packages_id = :package_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':package_id', $packageId);
        $stmt->execute();
    }
    public function addPapersToPackage($packageId, $papers) {
        foreach ($papers as $paper) {
            $sql = "INSERT INTO packages_has_papers (packages_id, papers_paper_id)
                    VALUES (:package_id, :paper_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':package_id', $packageId);
            $stmt->bindParam(':paper_id', $paper);
            $stmt->execute();
        }
    }
    public function removePapersFromPackage($packageId, $papers) {
        foreach ($papers as $paper) {
            $sql = "DELETE FROM packages_has_papers 
                    WHERE packages_id = :package_id AND papers_paper_id = :paper_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':package_id', $packageId);
            $stmt->bindParam(':paper_id', $paper);
            $stmt->execute();
        }
    }
    public function getAllPackages() {
        $sql = "SELECT * FROM packages";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllOptions() {
        $sql = "SELECT * FROM package_options";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createPackageOption($name) {
        try {
            $this->db->beginTransaction();
            $sql = "INSERT INTO package_options (package_options)
                    VALUES (:package_options)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':package_options', $name);
            $stmt->execute();
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception("Error creating package: " . $e->getMessage());
        }
    }

    public function updatePackageOption($id, $name)
    {
        try {
            $query = "UPDATE package_options SET package_options = :package_option WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':package_option', $name);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }
    public function __destruct()
    {
        $this->db = null; 
    }
}