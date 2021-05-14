import { Button } from '@wordpress/components';
import { MediaUpload } from '@wordpress/media-utils';

export const CustomImageField = () => {

	return (
		<MediaUpload
            allowedTypes={['image']}
            onSelect={(media) => { console.log(media) } }
            render={( {open} ) => (
                <Button isSecondary label="Edit file" onClick={open}>Set Custom Image</Button>
            )}
        />
	);
}