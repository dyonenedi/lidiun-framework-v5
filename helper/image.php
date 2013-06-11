<?php
###
# Open class controle de imagens do admin do site
# @Autor: Andre Teixeira
# @Data: 08/03/12
###

class Imagens {
	protected $imgSrc;
	protected $myImage;
	protected $cropHeight;
	protected $cropWidth;
	protected $x;
	protected $y;
	protected $thumb;

	public function setImage($image)
	{
		# Imagem
		$this->imgSrc = $image; 
		##
			 
		# captura as dimencoes da imagem
		list($width, $height) = getimagesize($this->imgSrc); 
		##
		 
		# cria uma imagem jpg ou png ou gif
		$this->myImage = @imagecreatefromjpeg($this->imgSrc);
		if( !$this->myImage )
		{
			$this->myImage = @imagecreatefrompng($this->imgSrc);
			if(!$this->myImage)
			{
				$this->myImage = @imagecreatefromgif($this->imgSrc);
			}
		}
		####
		
		##
		if( $width > $height )
		{
			$biggestSide = $width;		# encontra o lado maior
			$smallestSide = $height;	# encontra o lado menor
		}
			else
		{
			$biggestSide = $height;
			$smallestSide = $width;		# encontra o lado menor
		}
		###
		
		# The crop size will be half that of the largest side 
		$cropPercent = .5;			# This will zoom in to 50% zoom (crop)
		$this->cropWidth = $smallestSide;	# $biggestSide*$cropPercent; 
		$this->cropHeight = $smallestSide;	# $biggestSide*$cropPercent; 

		$this->Width = $width;			# $biggestSide*$cropPercent; 
		$this->Height = $height;		# $biggestSide*$cropPercent; 
					 
		# getting the top left coordinate
		$this->x = ($width-$this->Width)/2;
		$this->y = ($height-$this->Height)/2;    
	}

	public function createThumb($thumbwidth,$thumbheight=0,$proportion='nao')
	{
		$x = $y = 0;
		
		if(!$thumbheight)
			$thumbheight = $thumbwidth*$this->Height/$this->Width;
		if(!$thumbwidth)
			$thumbwidth = $thumbheight*$this->Width/$this->Height;
		
		$this->thumb = imagecreatetruecolor($thumbwidth, $thumbheight); 
	   
		$branco = imagecolorallocate($this->thumb, 255, 255, 255);
		imagefill($this->thumb, 0, 0, $branco);

		if( $proportion == 'enquadrar' )
		{
			if($this->Width > $this->Height)
			{
				$new_thumbheight = $thumbwidth*$this->Height/$this->Width;
				$new_thumbwidth = $thumbwidth;
			}
				else
			{
				$new_thumbwidth = $thumbheight*$this->Width/$this->Height;
				$new_thumbheight = $thumbheight;
			}

			$x = ( $thumbwidth - $new_thumbwidth ) / 2;
			$y = ( $thumbheight - $new_thumbheight ) / 2;
		}
			elseif( $proportion == 'preencher' )
		{
			$scale_width = $thumbwidth * 100 / $this->Width;
			$scale_height = $thumbheight * 100 / $this->Height;

			if($scale_width < $scale_height)
			{
				if($thumbheight < $this->Height)
					$new_thumbheight = $thumbheight;
				else
					$new_thumbheight = $this->Height;
				$new_thumbwidth = $new_thumbheight*$this->Width/$this->Height;
			}
				else
			{
				if($thumbwidth < $this->Width)
					$new_thumbwidth = $thumbwidth;
				else
					$new_thumbwidth = $this->Width;
				$new_thumbheight = $new_thumbwidth*$this->Height/$this->Width;
			}

			$x = ( $thumbwidth - $new_thumbwidth ) / 2;
			$y = ( $thumbheight - $new_thumbheight ) / 2;
		}
			elseif( $proportion == 'proporcional' ) 
		{
			if($this->Width > $this->Height && $this->Width > $thumbwidth)
			{
				$new_thumbwidth = $thumbwidth;
				$new_thumbheight = $thumbwidth*$this->Height/$this->Width;	
			}
				else
			{
				if($this->Height > $thumbwidth)
				{
					$new_thumbheight = $thumbwidth;
					$new_thumbwidth = $thumbwidth*$this->Width/$this->Height;	
				}
					else
				{
					$new_thumbwidth = $this->Width;
					$new_thumbheight = $this->Height;				
				}
			}
			$this->thumb = imagecreatetruecolor($new_thumbwidth, $new_thumbheight); 
		   
			$branco = imagecolorallocate($this->thumb, 255, 255, 255);
			imagefill($this->thumb, 0, 0, $branco);
		}
			elseif( $proportion == 'nao' ) 
		{
			$new_thumbwidth = $thumbwidth;
			$new_thumbheight = $thumbheight;
		}

		$thumbwidth = $new_thumbwidth;
		$thumbheight = $new_thumbheight;
		
		
		//echo '$thumbwidth: '.$thumbwidth.' | $thumbheight: '.$thumbheight.' | $this->Width: '.$this->Width.' | $this->Height: '.$this->Height.'';

		if(!imagecopyresampled($this->thumb, $this->myImage, $x, $y,$this->x, $this->y, $thumbwidth, $thumbheight, $this->Width, $this->Height)){
			return false;
		}else{
			return true;
		}

	}
	
	## Salva um arquivo no caminho desejado
	public function SalvarArquivo($Image, $SaveTo)
	{
		
		if(move_uploaded_file($Image, $SaveTo))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	###
	
	## Salva um arquivo no caminho desejado
	public function RedimencionaFoto($image,$path,$newPath)
	{

		move_uploaded_file($image,$path);

		$largura_alvo = 400;
		
		$img = imagecreatefromjpeg($path);

		$largura_original = imagesX($img);
		$altura_original = imagesY($img);

		$altura_nova = (int)($altura_original * $largura_alvo)/$largura_original;
		
		$novaImg = ImageCreateTrueColor($largura_alvo,$altura_nova);
		$branco = imagecolorallocate($novaImg, 255, 255, 255);
		imagefill($novaImg, 0, 0, $branco);
		
		imagecopyresampled($novaImg, $img, 0, 0, 0, 0, $largura_alvo, $altura_nova, $largura_original, $altura_original);

		if(imagejpeg($novaImg,$newPath,95)){
			return true;
		}else{
			return false;
		}
	}
	###

	public function renderImage()
	{                    
		header('Content-type: image/jpeg');
		imagejpeg($this->thumb);
		imagedestroy($this->thumb); 
	}

	public function saveImage($path){   
		return imagejpeg($this->thumb, $path, 95);
	}

}
?>
