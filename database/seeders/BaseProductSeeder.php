<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\File;

trait BaseProductSeeder
{
    /**
     * Tự động sinh một ảnh duy nhất (cover, thumbnail, banner...)
     */
    protected function generateSingleImage(string $folder, string $fileName): string
    {
        $storagePath = storage_path("app/public/seeders/products/{$folder}");
        $sourceImages = glob(public_path('assets/images/*.{jpg,png,webp}'), GLOB_BRACE);
        
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        $relativePath = "seeders/products/{$folder}/{$fileName}";
        
        if (!File::exists("{$storagePath}/{$fileName}") && !empty($sourceImages)) {
            $randomSource = $sourceImages[array_rand($sourceImages)];
            File::copy($randomSource, "{$storagePath}/{$fileName}");
        }

        return $relativePath;
    }

    /**
     * Tự động sinh danh sách ảnh tĩnh
     */
    protected function generateGallery(string $folder, int $count = 12): array
    {
        $images =[];
        $storagePath = storage_path("app/public/seeders/products/{$folder}");
        $sourceImages = glob(public_path('assets/images/*.{jpg,png,webp}'), GLOB_BRACE);
        
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        for ($i = 1; $i <= $count; $i++) {
            $fileName = "gallery-image-{$i}.jpg";
            $relativePath = "seeders/products/{$folder}/{$fileName}";
            
            if (!File::exists("{$storagePath}/{$fileName}") && !empty($sourceImages)) {
                $randomSource = $sourceImages[array_rand($sourceImages)];
                File::copy($randomSource, "{$storagePath}/{$fileName}");
            }
            
            $images[] = $relativePath;
        }
        
        return $images;
    }

    /**
     * Tự động bốc NGẪU NHIÊN danh sách ảnh từ một kho (pool) lớn.
     * Giúp mỗi sản phẩm chi tiết có ảnh thumbnail khác biệt nhau.
     */
    protected function generateRandomGallery(string $folder, int $poolSize = 30, int $pickCount = 10): array
    {
        $images =[];
        $storagePath = storage_path("app/public/seeders/products/{$folder}");
        $sourceImages = glob(public_path('assets/images/*.{jpg,png,webp}'), GLOB_BRACE);
        
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        // Tạo sẵn 30 ảnh vào kho
        for ($i = 1; $i <= $poolSize; $i++) {
            $fileName = "pool-image-{$i}.jpg";
            $relativePath = "seeders/products/{$folder}/{$fileName}";
            
            if (!File::exists("{$storagePath}/{$fileName}") && !empty($sourceImages)) {
                $randomSource = $sourceImages[array_rand($sourceImages)];
                File::copy($randomSource, "{$storagePath}/{$fileName}");
            }
            
            $images[] = $relativePath;
        }

        // Xáo trộn và lấy ra số lượng cần thiết
        shuffle($images);
        return array_slice($images, 0, $pickCount);
    }

    /**
     * Sinh mô tả chuẩn SEO dạng mảng (Array) cho các sản phẩm
     */
    protected function generateDescription(): array
    {
        $paragraphs =[
            'Sản phẩm được chế tác thủ công từ nguồn đất sét nguyên chất khai thác tại làng nghề Bát Tràng truyền thống, trải qua quá trình ủ đất kỹ lưỡng trong thời gian 6 tháng nhằm loại bỏ hoàn toàn tạp chất hữu cơ, đảm bảo độ dẻo dai và kết cấu đồng nhất cho từng sản phẩm trước khi đưa vào công đoạn tạo hình.',
            'Quy trình nung sản phẩm được thực hiện trong lò tuynel công nghệ cao ở nhiệt độ lên tới 1.250 độ C, duy trì liên tục trong 72 giờ. Nhiệt độ khắc nghiệt này giúp đất sét đạt đến trạng thái thiêu kết hoàn toàn, tạo ra sản phẩm có cường độ chịu lực vượt trội, khả năng chống thấm nước tuyệt đối và không bao giờ bị rêu mốc theo thời gian.',
            'Nguồn nguyên liệu đất sét được tuyển chọn nghiêm ngặt từ các mỏ đất trầm tích ven sông Hồng, giàu khoáng chất kaolinit tự nhiên. Đây là yếu tố then chốt giúp sản phẩm gốm Bát Tràng có màu sắc tự nhiên ấm áp, độ bền cơ học cao và khả năng thích ứng hoàn hảo với điều kiện khí hậu nhiệt đới ẩm đặc trưng của Việt Nam.',
            'Về mặt phong thủy kiến trúc, sản phẩm mang trong mình năng lượng của hành Thổ kết hợp với sức mạnh của hành Hỏa từ quá trình nung luyện. Sự kết hợp này tạo nên sự cân bằng âm dương hoàn hảo, giúp công trình kiến trúc vững bền, gia chủ an cư lạc nghiệp, thu hút tài lộc và may mắn.',
            'Trong kiến trúc đương đại, sản phẩm là sự giao thoa tinh tế giữa giá trị thẩm mỹ truyền thống hoài cổ và xu hướng thiết kế bền vững. Dù ứng dụng trong công trình đình chùa, nhà thờ họ hay biệt thự nghỉ dưỡng hiện đại, sản phẩm luôn mang lại một vẻ đẹp uy nghi, trường tồn và đậm đà bản sắc dân tộc Việt.',
        ];

        shuffle($paragraphs);
        return array_slice($paragraphs, 0, 4);
    }

    protected function generateVideoLink(): string
    {
        return 'https://www.youtube.com/embed/Win12rIicBI'; 
    }

    protected function generateSizeDescription(): array
    {
        return[
            'Kích thước sản phẩm được tính toán tỉ mỉ dựa trên nguyên lý mô-đun trong kiến trúc truyền thống và hiện đại, đảm bảo sự đồng bộ và chính xác tuyệt đối khi thi công trên mọi bề mặt công trình từ quy mô nhỏ đến lớn.',
            'Định mức thi công được kiểm định thực tế qua hàng trăm công trình dự án lớn nhỏ trên toàn quốc, giúp chủ đầu tư và đơn vị thi công dễ dàng dự toán vật tư, tránh lãng phí và tối ưu hóa chi phí xây dựng tối đa.',
            'Bề mặt sản phẩm được xử lý qua lớp men hỏa biến đặc biệt chống trơn trượt, đảm bảo an toàn tuyệt đối cho người sử dụng ngay cả trong điều kiện thời tiết nồm ẩm ướt, đồng thời vô cùng dễ dàng vệ sinh và bảo dưỡng định kỳ.',
        ];
    }
}