public function add()
{
    $data['title'] = 'Tambah Berita';
    $data['menu'] = 'news';
    return view('news/add', $data);
}
 
public function save()
{
    $rules = [
        'body' => 'required',
        'image_file'     => 'uploaded[image_file]|is_image[image_file]',
        'title' => 'required'
    ];
 
    if($this->validate($rules)){
        $model = new NewsModel();
        $fileImage_name = "";
        if(isset($_FILES) && @$_FILES['image_file']['error'] != '4') {
            if($fileImage = $this->request->getFile('image_file')) {
                if (! $fileImage->isValid()) {
                    throw new \RuntimeException($fileImage->getErrorString().'('.$fileImage->getError().')');
                } else {           
 
                    $fileImage->move('images/news');
                    $fileImage_name = $fileImage->getName();
                }
            }
        }
 
        $slug = $model->checkSlug(url_title($this->request->getVar('title'), '-', TRUE));
        $data = [
            'body' => $this->request->getVar('body'),
            'slug' => $slug,
            'title' => $this->request->getVar('title'),
            'image' => $fileImage_name,
            'publish_date'    => $this->request->getVar('publish_date')
        ];
         
        $model->save($data);
 
        return redirect()->to(base_url('/news/list?page=1'));
 
    } else {
        $data['validation'] = $this->validator;
        $data['title'] = 'Tambah Berita';
        $data['menu'] = 'news';
        return view('news/add', $data);
    }
 
}