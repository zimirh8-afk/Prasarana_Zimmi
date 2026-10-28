<?php

class Pengaduan extends Model
{
    protected $table = 'aspirasi';

    /**
     * Membuat pengaduan baru
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table}
                (
                    user_id,
                    kategori_id,
                    judul,
                    lokasi,
                    deskripsi,
                    foto,
                    status,
                    progress
                )
                VALUES
                (
                    :user_id,
                    :kategori_id,
                    :judul,
                    :lokasi,
                    :deskripsi,
                    :foto,
                    :status,
                    :progress
                )";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':user_id'     => $data['user_id'],
            ':kategori_id' => $data['kategori_id'],
            ':judul'       => $data['judul'],
            ':lokasi'      => $data['lokasi'],
            ':deskripsi'   => $data['deskripsi'],
            ':foto'        => $data['foto'],
            ':status'      => $data['status'],
            ':progress'    => $data['progress']
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Mengambil semua pengaduan
     */
    public function getAll()
    {
        $sql = "SELECT
                    p.*,
                    u.nama AS nama_siswa,
                    k.nama AS nama_kategori
                FROM {$this->table} p

                LEFT JOIN siswa s
                    ON p.user_id = s.id_siswa

                LEFT JOIN users u
                    ON s.id_user = u.id

                LEFT JOIN kategori k
                    ON p.kategori_id = k.id

                ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil pengaduan berdasarkan siswa
     */
    public function getByUser($userId)
    {
        $sql = "SELECT
                    p.*,
                    k.nama AS nama_kategori
                FROM {$this->table} p

                LEFT JOIN kategori k
                    ON p.kategori_id = k.id

                WHERE p.user_id = :user_id

                ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil detail pengaduan
     */
    public function getById($id)
    {
        $sql = "SELECT
                    p.*,
                    u.nama AS nama_siswa,
                    u.username,
                    k.nama AS nama_kategori
                FROM {$this->table} p

                LEFT JOIN siswa s
                    ON p.user_id = s.id_siswa

                LEFT JOIN users u
                    ON s.id_user = u.id

                LEFT JOIN kategori k
                    ON p.kategori_id = k.id

                WHERE p.id = :id

                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update status dan progress
     */
    public function updateStatus($id, $status, $progress)
    {
        $sql = "UPDATE {$this->table}
                SET
                    status = :status,
                    progress = :progress,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':status'   => $status,
            ':progress' => $progress,
            ':id'       => $id
        ]);
    }

    /**
     * Mengambil pengaduan terbaru
     */
    public function getLatest($limit = 5)
    {
        $limit = (int) $limit;

        $sql = "SELECT
                    p.*,
                    u.nama AS nama_siswa,
                    k.nama AS nama_kategori
                FROM {$this->table} p

                LEFT JOIN siswa s
                    ON p.user_id = s.id_siswa

                LEFT JOIN users u
                    ON s.id_user = u.id

                LEFT JOIN kategori k
                    ON p.kategori_id = k.id

                ORDER BY p.created_at DESC

                LIMIT {$limit}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Pengaduan terbaru berdasarkan siswa
     */
    public function getLatestByUser($userId, $limit = 5)
    {
        $limit = (int) $limit;

        $sql = "SELECT
                    p.*,
                    k.nama AS nama_kategori
                FROM {$this->table} p

                LEFT JOIN kategori k
                    ON p.kategori_id = k.id

                WHERE p.user_id = :user_id

                ORDER BY p.created_at DESC

                LIMIT {$limit}";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Menghitung seluruh pengaduan
     */
    public function countAll()
    {
        $sql = "SELECT COUNT(*) AS total
                FROM {$this->table}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    /**
     * Menghitung pengaduan berdasarkan status
     */
    public function countByStatus($status)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM {$this->table}
                WHERE status = :status";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':status' => $status
        ]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Menghitung pengaduan berdasarkan siswa
     */
    public function countByUser($userId)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM {$this->table}
                WHERE user_id = :user_id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':user_id' => $userId
        ]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Menghitung pengaduan siswa berdasarkan status
     */
    public function countByUserAndStatus($userId, $status)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM {$this->table}
                WHERE user_id = :user_id
                AND status = :status";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':user_id' => $userId,
            ':status' => $status
        ]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Filter pengaduan untuk Admin
     */
    public function getAllWithFilter($filter = [])
    {
        $sql = "SELECT
                    p.*,
                    u.nama AS nama_siswa,
                    u.username,
                    k.nama AS nama_kategori

                FROM {$this->table} p

                LEFT JOIN siswa s
                    ON p.user_id = s.id_siswa

                LEFT JOIN users u
                    ON s.id_user = u.id

                LEFT JOIN kategori k
                    ON p.kategori_id = k.id

                WHERE 1=1";

        $params = [];

        /**
         * Filter tanggal
         */
        if (!empty($filter['tanggal'])) {

            $sql .= " AND DATE(p.created_at) = :tanggal";

            $params[':tanggal'] = $filter['tanggal'];
        }

        /**
         * Filter bulan
         */
        if (!empty($filter['bulan'])) {

            $sql .= " AND DATE_FORMAT(
                        p.created_at,
                        '%Y-%m'
                      ) = :bulan";

            $params[':bulan'] = $filter['bulan'];
        }

        /**
         * Filter kategori
         */
        if (!empty($filter['kategori'])) {

            $sql .= " AND p.kategori_id = :kategori";

            $params[':kategori'] = $filter['kategori'];
        }

        /**
         * Filter siswa
         */
        if (!empty($filter['siswa'])) {

            $sql .= " AND p.user_id = :siswa";

            $params[':siswa'] = $filter['siswa'];
        }

        /**
         * Filter status
         */
        if (!empty($filter['status'])) {

            $sql .= " AND p.status = :status";

            $params[':status'] = $filter['status'];
        }

        /**
         * Search
         */
        if (!empty($filter['search'])) {

            $sql .= " AND (
                        p.judul LIKE :search1
                        OR p.lokasi LIKE :search2
                        OR u.nama LIKE :search3
                        OR k.nama LIKE :search4
                    )";

            $val = '%' . $filter['search'] . '%';
            $params[':search1'] = $val;
            $params[':search2'] = $val;
            $params[':search3'] = $val;
            $params[':search4'] = $val;
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Statistik berdasarkan kategori
     */
    public function countByCategory()
    {
        $sql = "SELECT
                    k.nama AS kategori,
                    COUNT(p.id) AS total

                FROM {$this->table} p

                LEFT JOIN kategori k
                    ON p.kategori_id = k.id

                GROUP BY p.kategori_id

                ORDER BY total DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Statistik pengaduan berdasarkan bulan
     */
    public function countByMonth()
    {
        $sql = "SELECT
                    DATE_FORMAT(created_at, '%Y-%m') AS bulan,
                    COUNT(*) AS total

                FROM {$this->table}

                GROUP BY DATE_FORMAT(created_at, '%Y-%m')

                ORDER BY bulan ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update data pengaduan (siswa edit)
     */
    public function updateData($id, $data)
    {
        $sql = "UPDATE {$this->table}
                SET judul       = :judul,
                    kategori_id = :kategori_id,
                    lokasi      = :lokasi,
                    deskripsi   = :deskripsi,
                    updated_at  = CURRENT_TIMESTAMP
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':judul'       => $data['judul'],
            ':kategori_id' => $data['kategori_id'],
            ':lokasi'      => $data['lokasi'],
            ':deskripsi'   => $data['deskripsi'],
            ':id'          => $id
        ]);
    }

    /**
     * Hapus pengaduan beserta histori dan feedback
     */
    public function delete($id)
    {
        $this->db->prepare("DELETE FROM histori_pengaduan WHERE pengaduan_id = :id")->execute([':id' => $id]);
        $this->db->prepare("DELETE FROM feedback WHERE pengaduan_id = :id")->execute([':id' => $id]);

        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

// Note: closing brace above ends countByMonth. Methods below added via append.
