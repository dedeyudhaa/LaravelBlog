@extends('back.layouts.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Edit Post')
@section('content')

<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <h2 class="page-title">
            Edit Post
          </h2>
        </div>
      </div>
    </div>
</div>

<form action="{{ route('author.posts.update-post',['post_id'=>Request('post_id')]) }}" method="post" id="editPostForm" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Post Title</label>
                        <input type="text" class="form-control" name="post_title" placeholder="Enter post title" value="{{ $post->post_title }}">
                        {{-- UNTUK ERRORNYA HARUS SAMA DENGAN NAME INPUTNYA --> post_title_error  --}}
                        <span class="text-danger error-text post_title_error"></span>
                    </div>
                    {{-- ckeditor 4 --}}
                    <div class="mb-3">
                        <label class="form-label">Post Content</label>
                        <textarea class="ckeditor form-control" name="post_content" rows="6" placeholder="Content.." id="post_content">{{ $post->post_content }}</textarea>
                        <span class="text-danger error-text post_content_error"></span>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <div class="form-label">Post Category</div>
                        <select class="form-select" name="post_category">
                          <option value="">No Selected</option>
                          @foreach (\App\Models\SubCategory::all() as $subcategory)
                              <option value="{{ $subcategory->id }}" {{ $post->subcategory_id == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->subcategory_name }}</option>
                          @endforeach
                        </select>
                        <span class="text-danger error-text post_category_error"></span>
                    </div>
                    <div class="mb-3">
                        <div class="form-label">Featured Image</div>
                        <input type="file" class="form-control" name="featured_image">
                        <span class="text-danger error-text featured_image_error"></span>
                    </div>
                    <div class="image_holder mb-2" style="max-width: 300px">
                        <img src="" alt="" class="img-thumbnail" id="image-previewer" data-ijabo-default-img='/storage/images/post_images/thumbnails/resized_{{ $post->featured_image }}'>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Post Tags</label>
                        <input type="text" class="form-control" name="post_tags" value="{{ $post->post_tags }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Post</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')

{{-- CKEDITOR 4 --}}
<script src="/ckeditor/ckeditor.js"></script>
    
{{-- AJAX --}}
<script>
    $(function(){
        $('input[type="file"][name="featured_image"]').ijaboViewer({
            preview:'#image-previewer',
            imageShape:'rectangular',
            allowedExtensions:['jpg','jpeg','png'],
            onErrorShape:function(message,element){
                alert(message);
            },
            onInvalidType:function(message,element){
                alert(message);
            }
        });

        $('form#editPostForm').on('submit', function(e){
            e.preventDefault();
            toastr.remove();
            var post_content = CKEDITOR.instances.post_content.getData();
            var form = this;
            var formdata = new FormData(form);
                formdata.append('post_content',post_content);

            $.ajax({
                url:$(form).attr('action'),
                method:$(form).attr('method'),
                data:formdata,
                processData:false,
                dataType:'json',
                contentType:false,
                beforeSend:function(){
                    $(form).find('span.error-text').text('');
                },

                success:function(response){
                    toastr.remove();
                    if(response.code == 1){
                        // $(form)[0].reset();
                        // $('div.image_holder').find('img').attr('src','');
                        // CKEDITOR.instances.post_content.setData('');
                        toastr.success(response.msg);
                    } else{
                        toastr.error(response.msg);
                    }
                },

                error:function(response){
                    toastr.remove();
                    $.each(response.responseJSON.errors, function(prefix,val){
                        $(form).find('span.'+prefix+'_error').text(val[0]);
                    });
                }

            });
        })
    });
</script>

@endpush