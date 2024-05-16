'use client';

import { useState } from "react";
import { UploadButton } from "../utils/uploadthing";

const ImageUpload = () => {

    const [imageUrl, setImageUrl] = useState<String> ('');

    return ( 
    <div>
        
        <UploadButton endpoint="imageUploader"         
        
        onClientUploadComplete={(res) => {
          console.log("Files: ", res);
          setImageUrl(res[0].url);
        }}
        onUploadError={(error: Error) => {
          alert(`ERROR! ${error.message}`);
        }}
        />

        {imageUrl.length ? 
        <div>
            <Image src={imageUrl} alt='my image' width={500} height={300}/>
        </div> : null}

    </div>
    );
};
export default ImageUpload;