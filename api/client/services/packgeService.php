<?php
class PackageService
{

    private $db;
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllPackages()
    {
        try {
            $query = "SELECT p.id AS package_id, p.package_name, p.package_price, p.package_description, po.id AS option_id, po.package_options, IF(pho.package_options_id IS NULL, 0, 1) AS has_option
            FROM packages p CROSS JOIN package_options po LEFT JOIN packages_has_options pho ON p.id = pho.packages_id AND po.id = pho.package_options_id ORDER BY p.id, po.id";

            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $packages = [];
            foreach ($results as $row) {
                $packageId = $row['package_id'];
                if (!isset($packages[$packageId])) {
                    $packages[$packageId] = [
                        'id' => $row['package_id'],
                        'name' => $row['package_name'],
                        'description' => $row['package_description'],
                        'price' => $row['package_price'],
                        'options' => []
                    ];
                }
                $optionText = ($row['has_option'] ? '' : 'No') . $row['package_options'];
                $packages[$packageId]['options'][] = $optionText;
            }

            return array_values($packages);
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    public function __destruct()
    {
        $this->db = null; 
    }
}
