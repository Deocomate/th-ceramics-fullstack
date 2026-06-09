<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\File;

trait BaseProductSeeder
{
    /**
     * Copy chính xác một hoặc nhiều file ảnh từ public/assets/images sang storage
     */
    protected function copySpecificImages(string $folder, array $filenames): array
    {
        $images = [];
        $storagePath = storage_path("app/public/seeders/products/{$folder}");

        if (! File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        foreach ($filenames as $fileName) {
            $sourcePath = public_path("assets/images/{$fileName}");
            $relativePath = "seeders/products/{$folder}/{$fileName}";
            $destinationPath = "{$storagePath}/{$fileName}";

            // Nếu file gốc tồn tại
            if (File::exists($sourcePath)) {
                // Copy nếu bên storage chưa có
                if (! File::exists($destinationPath)) {
                    File::copy($sourcePath, $destinationPath);
                }
                $images[] = $relativePath;
            }
        }

        return $images;
    }

    /**
     * Copy 1 ảnh duy nhất (Banner, Thumbnail...)
     */
    protected function copySingleImage(string $folder, string $fileName, string $fallbackName = 'logo.png'): string
    {
        $res = $this->copySpecificImages($folder, [$fileName]);
        if (empty($res)) {
            $res = $this->copySpecificImages($folder, [$fallbackName]);
        }

        return ! empty($res) ? $res[0] : '';
    }

    /**
     * Sinh mảng mô tả
     */
    protected function generateDescription(): array
    {
        $paragraphs = [
            'Sản phẩm được chế tác thủ công từ nguồn đất sét nguyên chất khai thác tại làng nghề Bát Tràng truyền thống, trải qua quá trình ủ đất kỹ lưỡng trong thời gian 6 tháng nhằm loại bỏ hoàn toàn tạp chất hữu cơ, đảm bảo độ dẻo dai và kết cấu đồng nhất.',
            'Quy trình nung sản phẩm được thực hiện trong lò tuynel công nghệ cao ở nhiệt độ lên tới 1.250 độ C, duy trì liên tục trong 72 giờ. Nhiệt độ khắc nghiệt này giúp đất sét đạt đến trạng thái thiêu kết hoàn toàn, tạo ra sản phẩm có cường độ chịu lực vượt trội.',
            'Nguồn nguyên liệu đất sét được tuyển chọn nghiêm ngặt từ các mỏ đất trầm tích ven sông Hồng, giàu khoáng chất kaolinit tự nhiên. Đây là yếu tố then chốt giúp sản phẩm gốm Bát Tràng có màu sắc tự nhiên ấm áp, độ bền cơ học cao.',
            'Về mặt phong thủy kiến trúc, sản phẩm mang trong mình năng lượng của hành Thổ kết hợp với sức mạnh của hành Hỏa từ quá trình nung luyện. Sự kết hợp này tạo nên sự cân bằng âm dương hoàn hảo, thu hút tài lộc và may mắn.',
            'Trong kiến trúc đương đại, sản phẩm là sự giao thoa tinh tế giữa giá trị thẩm mỹ truyền thống hoài cổ và xu hướng thiết kế bền vững. Ứng dụng phù hợp trong công trình đình chùa, nhà thờ họ hay biệt thự nghỉ dưỡng hiện đại.',
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
        return [
            'Kích thước sản phẩm được tính toán tỉ mỉ dựa trên nguyên lý mô-đun trong kiến trúc truyền thống và hiện đại.',
            'Định mức thi công được kiểm định thực tế qua hàng trăm công trình dự án lớn nhỏ trên toàn quốc.',
            'Bề mặt sản phẩm được xử lý qua lớp men hỏa biến đặc biệt chống trơn trượt, đảm bảo an toàn tuyệt đối.',
        ];
    }
}
